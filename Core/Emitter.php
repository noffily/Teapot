<?php

declare(strict_types=1);

namespace Noffily\Teapot\Core;

use Psr\Http\Message\ServerRequestInterface as PsrServerRequest;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Noffily\Teapot\Response\ResponseAdapterFactory;
use Closure;

final class Emitter
{
    private Closure $responseEmitter;

    public function __construct(Closure $responseEmitter)
    {
        $this->responseEmitter = $responseEmitter;
    }

    public function __invoke(PsrServerRequest|SymfonyRequest $serverRequest): ResponseAdapterFactory
    {
        $responseEmitter = $this->responseEmitter;
        return new ResponseAdapterFactory($responseEmitter($serverRequest));
    }
}
