<?php

declare(strict_types=1);

namespace Noffily\Teapot\Request;

use Noffily\Teapot\Exception\InvalidRequestType;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Psr\Http\Message\ServerRequestInterface as PsrRequest;

final class RequestAdapterFactory
{
    private SymfonyRequest|PsrRequest $request;

    public function __construct(SymfonyRequest|PsrRequest $request)
    {
        $this->request = $request;
    }

    public function create(): RequestAdapterInterface
    {
        if ($this->request instanceof SymfonyRequest) {
            return new SymfonyRequestAdapter($this->request);
        }

        if ($this->request instanceof PsrRequest) {
            return new Psr7RequestAdapter($this->request);
        }

        throw new InvalidRequestType();
    }
}
