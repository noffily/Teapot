<?php

declare(strict_types=1);

namespace Noffily\Teapot\Core;

use Psr\Http\Message\ResponseInterface;
use Closure;

final class ResponseEmitter
{
    private Closure $responseEmitter;

    public function __construct(Closure $responseEmitter)
    {
        $this->responseEmitter = $responseEmitter;
    }

    public function __invoke($serverRequest): ResponseInterface
    {
        $responseEmitter = $this->responseEmitter;
        return $responseEmitter($serverRequest);
    }
}
