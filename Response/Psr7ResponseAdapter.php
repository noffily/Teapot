<?php

declare(strict_types=1);

namespace Noffily\Teapot\Response;

use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Psr\Http\Message\ResponseInterface as PsrResponse;

final class Psr7ResponseAdapter implements ResponseAdapterInterface
{
    private PsrResponse $response;

    public function __construct(PsrResponse $response)
    {
        $this->response = $response;
    }

    public function adapt(): SymfonyResponse
    {
        return new SymfonyResponse(
            $this->getResponseContent(),
            $this->response->getStatusCode(),
            $this->response->getHeaders()
        );
    }

    private function getResponseContent(): string
    {
        $this->response->getBody()->isSeekable() && $this->response->getBody()->rewind();
        return $this->response->getBody()->getContents();
    }
}
