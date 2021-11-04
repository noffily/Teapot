<?php

declare(strict_types=1);

namespace Noffily\Teapot\Core;

use Noffily\Teapot\Data\TestCase;
use Noffily\Teapot\Exception\CircularDependencyException;
use Noffily\Teapot\Exception\TeapotException;
use Noffily\Teapot\Exception\TestNotFoundException;
use Noffily\Teapot\Interface\ErrorCollectorInterface;

use function in_array;

final class TestSorter
{
    /** @var array<TestCase> */
    private array $tests;
    private array $sortedTests = [];
    private ErrorCollectorInterface $errorCollector;

    public function __construct(array $tests, ErrorCollectorInterface $errorCollector)
    {
        $this->tests = $tests;
        $this->errorCollector = $errorCollector;
    }

    /**
     * @return array<TestCase>
     */
    public function sort(): void
    {
        $this->sortedTests = [];
        $this->errorCollector->resetErrors();
        foreach ($this->tests as $test) {
            try {
                $this->process($test);
            } catch (TeapotException $e) {
                $this->errorCollector->addError($e->getMessage());
            }
        }
    }

    /**
     * @return array<TestCase>
     */
    public function getSortedTests(): array
    {
        return $this->sortedTests;
    }

    public function getErrorCollector(): ErrorCollectorInterface
    {
        return $this->errorCollector;
    }

    /**
     * @throws TestNotFoundException
     * @throws CircularDependencyException
     */
    protected function process(TestCase $testCase, array $parents = []): void
    {
        if (in_array((string) $testCase, $parents, true)) {
            $parents[] = (string) $testCase;
            throw new CircularDependencyException(
                'Circular dependency found in %s',
                implode('->', $parents)
            );
        }

        if ($testCase->isSortProcessed()) {
            return;
        }
        $testCase->markSortProcessed();

        foreach ($testCase->getDepends() as $depend) {
            if (!isset($this->tests[(string) $depend])) {
                throw new TestNotFoundException(
                    sprintf('Depend %s that defined in %s not found.', $depend, $testCase)
                );
            }
            $parents[] = (string) $testCase;
            $this->process($this->tests[(string) $depend], $parents);
        }

        $this->addSortedTest($testCase);
    }

    private function addSortedTest(TestCase $testCase): void
    {
        $this->sortedTests[] = $testCase;
    }
}
