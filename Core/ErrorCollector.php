<?php

declare(strict_types=1);

namespace Noffily\Teapot\Core;

use Noffily\Teapot\Interface\ErrorCollectorInterface;

class ErrorCollector implements ErrorCollectorInterface
{
    private array $errors = [];

    public function addError(string $error): void
    {
        $this->errors[] = $error;
    }

    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function resetErrors(): void
    {
        $this->errors = [];
    }
}
