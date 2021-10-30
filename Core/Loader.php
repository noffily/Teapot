<?php

declare(strict_types=1);

namespace Noffily\Teapot\Core;

use Throwable;
use ReflectionClass;
use ReflectionMethod;
use ReflectionException;
use SebastianBergmann\FileIterator\Facade;
use Noffily\Teapot\Data\Config;
use Noffily\Teapot\Data\TestCase;

final class Loader
{
    /** @var array<TestCase>  */
    private array $tests = [];

    public function __construct(Config $config)
    {
        // todo: need to be extracted
        $files = (new Facade())->getFilesAsArray(
            $config->getDirectory(),
            $config->getSuffix(),
            $config->getPrefix(),
            $config->getExclude()
        );
        $declaredClasses = get_declared_classes();
        $tests = [];
        foreach ($files as $file) {
            include_once $file;
        }
        $tests = array_values(array_diff(get_declared_classes(), $declaredClasses));

        foreach ($tests as $test) {
            try {
                $class = new ReflectionClass($test);
            } catch (ReflectionException) {
                continue;
            }

            if ($class->isAbstract() || $class->getConstructor()?->getNumberOfRequiredParameters() > 0) {
                continue;
            }

            $cases = [];
            $methods = $class->getMethods(ReflectionMethod::IS_PUBLIC);
            foreach ($methods as $method) {

                if ($method->getNumberOfParameters() < 1 || $method->getNumberOfRequiredParameters() > 1) {
                    continue 2;
                }

                if ($method->getParameters()[0]->getType()?->getName() !== Runner::class) {
                    continue 2;
                }

                $cases[] = $method->getName();
            }

            if (empty($cases)) {
                continue;
            }

            // todo: test must be a collection object
            $this->tests[] = new TestCase($test, $cases);
        }
    }

    public function getTests(): array
    {
        return $this->tests;
    }

    public function execute(Runner $runner): void
    {
        // todo move it to another class
        foreach ($this->tests as $item) {
            $test = $item->getTest();
            $testClass = new $test();
            foreach ($item->getCases() as $case) {
                try {
                    $testClass->$case($runner);
                    echo sprintf('%s::%s: OK!', $test, $case) . PHP_EOL;
                } catch (Throwable) {
                    echo sprintf('%s::%s: FAILED!', $test, $case) . PHP_EOL;
                }
            }
        }
    }
}