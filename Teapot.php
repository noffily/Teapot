<?php

declare(strict_types=1);

namespace Noffily\Teapot;

use Throwable;
use Noffily\Teapot\Core\CliOutput;
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
    private CliOutput $cliOutput;

    public function __construct(RequestEmitter $requestEmitter, Config $config)
    {
        $this->requestEmitter = $requestEmitter;
        $this->loader = new TestLoader($config, new Facade(), new ErrorCollector());
        $this->cliOutput = new CliOutput();
    }

    public function run(): void
    {
        $tests = $this->loader->getTests();
        foreach ($this->loader->getErrorCollector()->get() as $error) {
            echo $error . PHP_EOL;
        }

        $sorter = new TestSorter($tests, new ErrorCollector());
        $sorter->sort();
        foreach ($sorter->getErrorCollector()->get() as $error) {
            echo $error . PHP_EOL;
        }

        foreach ($sorter->getSortedTests() as $item) {
            $this->cliOutput->resetCount()->progress()->text(sprintf(' %s:', $item));
            $outputCount = $this->cliOutput->count();
            if ($item->isSkipped()) {
                $this->cliOutput
                    ->backspace($outputCount)
                    ->cyan()
                    ->skipped()
                    ->text(sprintf(' %s: SKIPPED!', $item))
                    ->reset()
                    ->newLine();
                continue;
            }
            $test = $item->getTest();
            $case = $item->getCase();
            $testClass = new $test();
            try {
                $testClass->$case($this->requestEmitter);
                $this->cliOutput
                    ->backspace($outputCount)
                    ->green()
                    ->passed()
                    ->text(sprintf(' %s: PASSED!', $item))
                    ->reset()
                    ->newLine();
            } catch (Throwable) {
                $this->cliOutput
                    ->backspace($outputCount)
                    ->red()
                    ->failed()
                    ->text(sprintf(' %s: FAILED!', $item))
                    ->reset()
                    ->newLine();
            }
        }
    }
}
