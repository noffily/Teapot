<?php

declare(strict_types=1);

namespace Noffily\Teapot\Core;

use Noffily\Teapot\Interface\ErrorCollectorInterface;

final class ErrorCollector implements ErrorCollectorInterface
{
    private array $errors = [];

    public function add(string $error): void
    {
        $this->errors[] = $error;
    }

    public function has(): bool
    {
        return count($this->errors) > 0;
    }

    public function get(): array
    {
        return $this->errors;
    }

    public function reset(): void
    {
        $this->errors = [];
    }
}
