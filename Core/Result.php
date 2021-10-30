<?php

declare(strict_types=1);

namespace Noffily\Teapot\Core;

use PHPUnit\Framework\Assert;
use Noffily\Teapot\Data\Response;

class Result
{
    private Response $response;

    public function __construct(Response $response)
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
        Assert::assertSame($contents, $this->getResponse()->getBodyContents(), $message);
    }

    protected function getResponse(): Response
    {
        return $this->response;
    }
}
