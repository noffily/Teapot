<?php

declare(strict_types=1);

namespace Noffily\Teapot;

use Throwable;
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
        $this->loader = new TestLoader($config, new Facade());
    }

    public function run(): void
    {
        foreach ($this->loader->getTests() as $item) {
            $test = $item->getTest();
            $testClass = new $test();

            if (empty($item->getCases())) {
                echo sprintf('%s has no test cases: SKIPPING!', $test);
            }

            foreach ($item->getCases() as $case) {
                try {
                    $testClass->$case($this->requestEmitter);
                    echo sprintf('%s::%s: OK!', $test, $case) . PHP_EOL;
                } catch (Throwable) {
                    echo sprintf('%s::%s: FAILED!', $test, $case) . PHP_EOL;
                }
            }
        }
    }
}
