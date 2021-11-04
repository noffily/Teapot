<?php

declare(strict_types=1);

namespace Noffily\Teapot\Interface;

interface ErrorCollectorInterface
{
    public function add(string $error): void;

    public function has(): bool;

    public function get(): array;

    public function reset(): void;
}
