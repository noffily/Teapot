<?php

declare(strict_types=1);

namespace Noffily\Teapot\Core;

use Throwable;
use Exception;
use ReflectionClass;
use ReflectionMethod;
use ReflectionException;
use SebastianBergmann\FileIterator\Facade;
use Noffily\Teapot\Attribute\Depends;
use Noffily\Teapot\Attribute\Skipped;
use Noffily\Teapot\Data\Config;
use Noffily\Teapot\Data\TestCase;
use Noffily\Teapot\Interface\ErrorCollectorInterface;
use Noffily\Teapot\Exception\EmptyCasesException;

final class TestLoader
{
    private Config $config;
    private Facade $facade;
    private ErrorCollectorInterface $errorCollector;

    public function __construct(Config $config, Facade $facade, ErrorCollectorInterface $errorCollector)
    {
        $this->config = $config;
        $this->facade = $facade;
        $this->errorCollector = $errorCollector;
    }

    /**
     * @return array<TestCase>
     */
    public function getTests(): array
    {
        $testClasses = $this->getTestClasses();
        $tests = [];
        foreach ($testClasses as $testClass) {
            try {
                $tests[] = $this->getTestCases($testClass);
            } catch (EmptyCasesException $e) {
                $this->errorCollector->add($e->getMessage());
                continue;
            } catch (Throwable) {
                continue;
            }
        }
        return array_merge(...$tests);
    }

    public function getErrorCollector(): ErrorCollectorInterface
    {
        return $this->errorCollector;
    }

    /**
     * @return array
     */
    protected function getTestClasses(): array
    {
        $files = $this->facade->getFilesAsArray(
            $this->config->getDirectory(),
            $this->config->getSuffix(),
            $this->config->getPrefix(),
            $this->config->getExclude()
        );
        $declaredClasses = get_declared_classes();
        $tests = [];
        foreach ($files as $file) {
            include_once $file;
        }
        return array_values(array_diff(get_declared_classes(), $declaredClasses));
    }

    /**
     * @param string $test
     * @return array
     * @throws ReflectionException
     * @throws Exception
     */
    protected function getTestCases(string $test): array
    {
        $class = new ReflectionClass($test);
        if ($class->isAbstract() || $class->getConstructor()?->getNumberOfRequiredParameters() > 0) {
            throw new Exception(sprintf(
                'Test class %s must not be an abstract and must have no required parameters.',
                $test
            ));
        }

        $cases = [];
        foreach ($class->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if ($method->getNumberOfParameters() < 1 || $method->getNumberOfRequiredParameters() > 1) {
                continue;
            }

            if ($method->getParameters()[0]->getType()?->getName() !== RequestEmitter::class) {
                continue;
            }

            $dependsAttributes = $method->getAttributes(Depends::class);
            $depends = [];
            foreach ($dependsAttributes as $dependsAttribute) {
                $depends[] = $dependsAttribute->newInstance();
            }
            $skipped = count($method->getAttributes(Skipped::class)) > 0;
            $testCase = new TestCase($test, $method->getName(), $depends, $skipped);
            $cases[(string) $testCase] = $testCase;
        }

        if (empty($cases)) {
            throw new EmptyCasesException(sprintf('%s has no test cases: SKIPPING!', $test));
        }

        return $cases;
    }
}
