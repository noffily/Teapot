<?php

declare(strict_types=1);

namespace Noffily\Psr7\Test;

use Psr\Http\Message\{ServerRequestInterface, ResponseInterface};
use Closure;

final class Emitter
{
    private Closure $responseEmitter;

    public function __construct(Closure $responseEmitter)
    {
        $this->responseEmitter = $responseEmitter;
    }

    public function __invoke(ServerRequestInterface $serverRequest): ResponseInterface
    {
        $responseEmitter = $this->responseEmitter;
        return $responseEmitter($serverRequest);
    }
}
