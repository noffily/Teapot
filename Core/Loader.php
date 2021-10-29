<?php

declare(strict_types=1);

namespace Noffily\Teapot\Core;

use Throwable;
use ReflectionClass;
use ReflectionMethod;
use SebastianBergmann\FileIterator\Facade;

final class Loader
{
    private array $tests = [];

    public function __construct(string $directory, string $prefix = '', string $suffix = '.php', array $exclude = [])
    {
        // todo: need to be extracted
        $files = (new Facade())->getFilesAsArray($directory, $suffix, $prefix, $exclude);
        $declaredClasses = get_declared_classes();
        $tests = [];
        foreach ($files as $file) {
            include_once $file;

            $tests = array_diff(get_declared_classes(), $declaredClasses);
        }
        $tests = array_values($tests);

        $loaded = [];

        foreach ($tests as $test) {
            $class = new ReflectionClass($test);

            if ($class->isAbstract() || $class->getConstructor()?->getNumberOfRequiredParameters() > 0) {
                continue;
            }

            $testCases = [];
            $methods = $class->getMethods(ReflectionMethod::IS_PUBLIC);
            foreach ($methods as $method) {

                if ($method->getNumberOfParameters() < 1 || $method->getNumberOfRequiredParameters() > 1) {
                    continue 2;
                }

                if ($method->getParameters()[0]->getType()?->getName() !== Runner::class) {
                    continue 2;
                }

                $testCases[] = $method->getName();
            }

            if (empty($testCases)) {
                continue;
            }

            // todo: needs to be an object
            $this->tests[] = [
                'test' => $test,
                'cases' => $testCases,
            ];
        }
    }

    public function getTests(): array
    {
        return $this->tests;
    }

    public function execute(Runner $runner)
    {
        // todo move it to another class
        foreach ($this->tests as $item) {
            $test = $item['test'];
            $cases = $item['cases'];

            $testClass = new $test();
            foreach ($cases as $case) {
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