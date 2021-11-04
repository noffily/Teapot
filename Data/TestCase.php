<?php

declare(strict_types=1);

namespace Noffily\Teapot\Data;

final class TestCase
{
    private string $test;
    private string $case;
    private array $depends;
    private bool $skipped;
    private bool $sortProcessed;

    public function __construct(
        string $test,
        string $case,
        array $depends = [],
        bool $skipped = false,
        bool $sortProcessed = false
    ) {
        $this->test = $test;
        $this->case = $case;
        $this->depends = $depends;
        $this->skipped = $skipped;
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

    public function getDepends(): array
    {
        return $this->depends;
    }

    public function isSkipped(): bool
    {
        return $this->skipped;
    }

    public function markSortProcessed(): void
    {
        $this->sortProcessed = true;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getTest() . '::' . $this->getCase();
    }
}
