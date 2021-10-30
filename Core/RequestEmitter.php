<?php

declare(strict_types=1);

namespace Noffily\Teapot\Core;

use Noffily\Teapot\Data\Response;

final class RequestEmitter
{
    private ResponseEmitter $responseEmitter;

    public function __construct(ResponseEmitter $responseEmitter)
    {
        $this->responseEmitter = $responseEmitter;
    }

    public function run(mixed $request): ResponseInspector
    {
        $responseEmitter = $this->responseEmitter;
        return new ResponseInspector(new Response($responseEmitter($request)));
    }
}
