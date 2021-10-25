<?php

declare(strict_types=1);

namespace Noffily\Teapot\Core;

use PHPUnit\Framework\Assert;
use Psr\Http\Message\ResponseInterface;

class Result
{
    private ResponseInterface $response;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * Checks that response code is equal to provided value.
     * @param int $code
     * @param string $message
     */
    public function seeResponseCodeIs(int $code, string $message = ''): void
    {
        Assert::assertEquals($code, $this->getResponse()->getStatusCode(), $message);
    }

    /**
     * Checks that response body contents matches the one provided
     * @param string $contents
     * @param string $message
     */
    public function seeResponseBodyContentsIs(string $contents, string $message = ''): void
    {
        Assert::assertSame($contents, $this->getResponseContent(), $message);
    }

    protected function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    protected function getResponseContent(): string
    {
        $this->getResponse()->getBody()->isSeekable() && $this->getResponse()->getBody()->rewind();
        return $this->getResponse()->getBody()->getContents();
    }
}
