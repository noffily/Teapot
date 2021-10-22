<?php

declare(strict_types=1);

namespace Noffily\Psr7\Test;

use Psr\Http\Message\ServerRequestInterface;

final class Runner
{
    private ServerRequestInterface $request;
    private Emitter $emitter;

    public function __construct(Emitter $emitter)
    {
        $this->emitter = $emitter;
    }

    public function addRequest(ServerRequestInterface $request): void
    {
        $this->request = $request;
    }

    public function execute(): Result
    {
        $emitter = $this->emitter;
        return new Result($this->request, $emitter($this->request));
    }
}