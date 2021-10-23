<?php

declare(strict_types=1);

namespace Noffily\Teapot\Response;

use Noffily\Teapot\Exception\InvalidResponseType;
use Psr\Http\Message\ResponseInterface as PsrResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

final class ResponseAdapterFactory
{
    private PsrResponse|SymfonyResponse $response;

    public function __construct(PsrResponse|SymfonyResponse $response)
    {
        $this->response = $response;
    }

    public function create(): ResponseAdapterInterface
    {
        if ($this->response instanceof PsrResponse) {
            return new Prs7ResponseAdapter($this->response);
        }

        if ($this->response instanceof SymfonyResponse) {
            return new SymfonyResponseAdapter($this->response);
        }

        throw new InvalidResponseType();
    }
}
