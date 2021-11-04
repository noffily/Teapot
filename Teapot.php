<?php

declare(strict_types=1);

namespace Noffily\Teapot;

use Throwable;
use Noffily\Teapot\Core\ErrorCollector;
use Noffily\Teapot\Core\TestSorter;
use Noffily\Teapot\Core\TestLoader;
use Noffily\Teapot\Core\RequestEmitter;
use Noffily\Teapot\Data\Config;
use SebastianBergmann\FileIterator\Facade;

final class Teapot
{
    private RequestEmitter $requestEmitter;
    private TestLoader $loader;

    public function __construct(RequestEmitter $requestEmitter, Config $config)
    {
        $this->requestEmitter = $requestEmitter;
        $this->loader = new TestLoader($config, new Facade(), new ErrorCollector());
    }

    public function run(): void
    {
        $tests = $this->loader->getTests();
        foreach ($this->loader->getErrorCollector()->getErrors() as $error) {
            echo $error . PHP_EOL;
        }

        $sorter = new TestSorter($tests, new ErrorCollector());
        $sorter->sort();
        foreach ($sorter->getErrorCollector()->getErrors() as $error) {
            echo $error . PHP_EOL;
        }

        foreach ($sorter->getSortedTests() as $item) {
            if ($item->isSkipped()) {
                echo sprintf('%s: SKIPPED!', $item) . PHP_EOL;
            }
            $test = $item->getTest();
            $case = $item->getCase();
            $testClass = new $test();
            try {
                $testClass->$case($this->requestEmitter);
                echo sprintf('%s: OK!', $item) . PHP_EOL;
            } catch (Throwable) {
                echo sprintf('%s: FAILED!', $item) . PHP_EOL;
            }
        }
    }
}
