<?php

declare(strict_types=1);

namespace Noffily\Teapot\Core;

final class Runner
{
    private Emitter $emitter;

    public function __construct(Emitter $emitter)
    {
        $this->emitter = $emitter;
    }

    public function execute($request): Result
    {
        $emitter = $this->emitter;
        return new Result($request, $emitter($request));
    }
}