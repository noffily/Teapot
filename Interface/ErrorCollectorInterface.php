<?php

declare(strict_types=1);

namespace Noffily\Teapot\Interface;

interface ErrorCollectorInterface
{
    public function addError(string $error): void;

    public function hasErrors(): bool;

    public function getErrors(): array;

    public function resetErrors(): void;
}
