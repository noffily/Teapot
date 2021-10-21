<?php

declare(strict_types=1);

namespace Noffily\Psr7\Test;

use Psr\Http\Message\ServerRequestInterface;

final class Runner
{
    private array $requests = [];
    private Emitter $emitter;

    public function __construct(Emitter $emitter)
    {
        $this->emitter = $emitter;
    }

    public function addRequest(ServerRequestInterface $request): void
    {
        $this->requests[] = $request;
    }

    /**
     * @return array<Result>
     */
    public function execute(): array
    {
        $results = [];
        $emitter = $this->emitter;

        foreach ($this->requests as $request) {
            $results[] = new Result($request, $emitter($request));
        }

        return $results;
    }
}