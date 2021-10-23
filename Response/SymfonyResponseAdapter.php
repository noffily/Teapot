<?php

declare(strict_types=1);

namespace Noffily\Teapot\Response;

use Symfony\Component\HttpFoundation\Response;

final class SymfonyResponseAdapter implements ResponseAdapterInterface
{
    private Response $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function adapt(): Response
    {
        return $this->response;
    }
}
