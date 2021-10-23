<?php

declare(strict_types=1);

namespace Noffily\Teapot\Request;

use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Psr\Http\Message\ServerRequestInterface as PsrRequest;

final class Psr7RequestAdapter implements RequestAdapterInterface
{
    private PsrRequest $request;

    public function __construct(PsrRequest $request)
    {
        $this->request = $request;
    }

    public function adapt(): SymfonyRequest
    {
        $this->request->getBody()->isSeekable() && $this->request->getBody()->rewind();

        return new SymfonyRequest(
            $this->request->getQueryParams(),
            (array) $this->request->getParsedBody(),
            $this->request->getAttributes(),
            $this->request->getCookieParams(),
            $this->request->getUploadedFiles(),
            $this->request->getServerParams(),
            $this->request->getBody()->getContents()
        );
    }
}
