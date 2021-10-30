<?php

declare(strict_types=1);

namespace Noffily\Teapot;

use Noffily\Teapot\Core\Loader;
use Noffily\Teapot\Core\Runner;
use Noffily\Teapot\Data\Config;

final class Teapot
{
    private Runner $runner;
    private Loader $loader;

    public function __construct(Runner $runner, Config $config)
    {
        $this->runner = $runner;
        $this->loader = new Loader($config);
    }

    public function run(): void
    {
        $this->loader->execute($this->runner);
    }
}
