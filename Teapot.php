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

    // todo refactoring
    private array $failed = [];
    private array $skipped = [];
    private array $passed = [];
    private array $incomplete = [];

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
            $this->cliOutput->red()->failed()->text($error)->newLine();
        }

        $sorter = new TestSorter($tests, new ErrorCollector());
        $sorter->sort();
        foreach ($sorter->getErrorCollector()->get() as $error) {
            $this->cliOutput->red()->failed()->text($error)->newLine();
        }

        foreach ($sorter->getSortedTests() as $item) {
            $this->cliOutput->resetCount()->progress()->text(sprintf(' %s:', $item));
            $outputCount = $this->cliOutput->count();
            if ($item->isSkipped()) {
                $this->skipped[(string) $item] = $item;
                $this->cliOutput
                    ->backspace($outputCount)
                    ->cyan()
                    ->skipped()
                    ->text(sprintf(' %s: SKIPPED!', $item))
                    ->reset()
                    ->newLine();
                continue;
            }

            if (array_intersect_key(
                $item->getDepends(),
                array_merge($this->failed, $this->skipped, $this->incomplete)
            )) {
                $this->incomplete[(string) $item] = $item;
                $this->cliOutput
                    ->backspace($outputCount)
                    ->yellow()
                    ->incomplete()
                    ->text(sprintf(' %s: INCOMPLETE!', $item))
                    ->reset()
                    ->newLine();
                continue;
            }

            $test = $item->getTest();
            $case = $item->getCase();
            $testClass = new $test();
            try {
                $testClass->$case($this->requestEmitter);
                $this->passed[(string) $item] = $item;
                $this->cliOutput
                    ->backspace($outputCount)
                    ->green()
                    ->passed()
                    ->text(sprintf(' %s: PASSED!', $item))
                    ->reset()
                    ->newLine();
            } catch (Throwable) {
                $this->failed[(string) $item] = $item;
                $this->cliOutput
                    ->backspace($outputCount)
                    ->red()
                    ->failed()
                    ->text(sprintf(' %s: FAILED!', $item))
                    ->reset()
                    ->newLine();
            }
        }
        $this->cliOutput->newLine()->results()->text(' RESULTS:')->newLine();
        $this->cliOutput
            ->green()
            ->passed()
            ->text(sprintf(' Count passed: %d', count($this->passed)))
            ->reset()
            ->newLine();
        $this->cliOutput
            ->red()
            ->failed()
            ->text(sprintf(' Count failed: %d', count($this->failed)))
            ->reset()
            ->newLine();
        $this->cliOutput
            ->cyan()
            ->skipped()
            ->text(sprintf(' Count skipped: %d', count($this->skipped)))
            ->reset()
            ->newLine();
        $this->cliOutput
            ->yellow()
            ->incomplete()
            ->text(sprintf(' Count incomplete: %d', count($this->incomplete)))
            ->reset()
            ->newLine();
    }
}
