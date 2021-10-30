<?php

declare(strict_types=1);

namespace Noffily\Teapot\Core;

use Noffily\Teapot\Data\Response;

final class Runner
{
    private Emitter $emitter;

    public function __construct(Emitter $emitter)
    {
        $this->emitter = $emitter;
    }

    public function execute(mixed $request): Result
    {
        $emitter = $this->emitter;
        return new Result(new Response($emitter($request)));
    }
}
