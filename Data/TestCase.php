<?php

declare(strict_types=1);

namespace Noffily\Teapot\Data;

final class TestCase
{
    private string $test;
    private string $case;
    private array $depends;
    private bool $sortProcessed;

    public function __construct(string $test, string $case, array $depends = [], bool $sortProcessed = false)
    {
        $this->test = $test;
        $this->case = $case;
        $this->depends = $depends;
        $this->sortProcessed = $sortProcessed;
    }

    /**
     * @return string
     */
    public function getTest(): string
    {
        return $this->test;
    }

    /**
     * @return string
     */
    public function getCase(): string
    {
        return $this->case;
    }

    public function isSortProcessed(): bool
    {
        return $this->sortProcessed;
    }

    public function markSortProcessed(): void
    {
        $this->sortProcessed = true;
    }

    public function getDepends(): array
    {
        return $this->depends;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getTest() . '::' . $this->getCase();
    }
}
