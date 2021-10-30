<?php

declare(strict_types=1);

namespace Noffily\Teapot;

use Noffily\Teapot\Core\TestLoader;
use Noffily\Teapot\Core\RequestEmitter;
use Noffily\Teapot\Data\Config;

final class Teapot
{
    private RequestEmitter $requestEmitter;
    private TestLoader $loader;

    public function __construct(RequestEmitter $requestEmitter, Config $config)
    {
        $this->requestEmitter = $requestEmitter;
        $this->loader = new TestLoader($config);
    }

    public function run(): void
    {
        $this->loader->execute($this->requestEmitter);
    }
}
