<?php

declare(strict_types=1);

namespace Noffily\Teapot\Request;

use Symfony\Component\HttpFoundation\Request;

final class SymfonyRequestAdapter implements RequestAdapterInterface
{
    private Request $request;

    public function __construct(Request $response)
    {
        $this->request = $response;
    }

    public function adapt(): Request
    {
        return $this->request;
    }
}
