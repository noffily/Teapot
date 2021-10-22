<?php

declare(strict_types=1);

namespace Noffily\Psr7\Test;

use PHPUnit\Framework\Assert;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class Result
{
    private ServerRequestInterface $request;
    private ResponseInterface $response;

    public function __construct(ServerRequestInterface $request, ResponseInterface $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * Checks that response code is equal to value provided.
     *
     * @param int $code
     * @param string $message
     */
    public function seeResponseCodeIs(int $code, string $message = ''):void
    {
        Assert::assertEquals($code, $this->getResponse()->getStatusCode(), $message);
    }

    protected function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }

    protected function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}
