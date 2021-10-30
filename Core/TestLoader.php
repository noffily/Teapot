<?php

declare(strict_types=1);

namespace Noffily\Teapot\Core;

use Throwable;
use Exception;
use ReflectionClass;
use ReflectionMethod;
use ReflectionException;
use SebastianBergmann\FileIterator\Facade;
use Noffily\Teapot\Data\Config;
use Noffily\Teapot\Data\TestCase;

final class TestLoader
{
    private Config $config;
    private Facade $facade;

    public function __construct(Config $config, Facade $facade)
    {
        $this->config = $config;
        $this->facade = $facade;
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
                $tests[] = $this->getTestCase($testClass);
            } catch (Throwable) {
                continue;
            }
        }
        return $tests;
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
     * @return TestCase
     * @throws ReflectionException
     * @throws Exception
     */
    protected function getTestCase(string $test): TestCase
    {
        $class = new ReflectionClass($test);
        if ($class->isAbstract() || $class->getConstructor()?->getNumberOfRequiredParameters() > 0) {
            throw new Exception(sprintf(
            'Test class %s must not be an abstract and must have no required parameters.',
                $test
            ));
        }

        return new TestCase($test, $this->getTestMethods($class));
    }

    /**
     * @param ReflectionClass $class
     * @return array
     */
    protected function getTestMethods(ReflectionClass $class): array
    {
        $cases = [];
        foreach ($class->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {

            if ($method->getNumberOfParameters() < 1 || $method->getNumberOfRequiredParameters() > 1) {
                continue;
            }

            if ($method->getParameters()[0]->getType()?->getName() !== RequestEmitter::class) {
                continue;
            }

            $cases[] = $method->getName();
        }
        return $cases;
    }
}
