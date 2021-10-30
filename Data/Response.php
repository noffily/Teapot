<?php

declare(strict_types=1);

namespace Noffily\Teapot\Data;

use Psr\Http\Message\ResponseInterface;

final class Response
{
    private ResponseInterface $response;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    public function getStatusCode(): int
    {
        return $this->getResponse()->getStatusCode();
    }

    public function getBodyContents(): string
    {
        $this->getResponse()->getBody()->isSeekable() && $this->getResponse()->getBody()->rewind();
        return $this->getResponse()->getBody()->getContents();
    }
}
