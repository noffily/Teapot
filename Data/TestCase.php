<?php

declare(strict_types=1);

namespace Noffily\Teapot\Data;

final class TestCase
{
    private string $test;
    private array $cases;

    public function __construct(string $test, array $cases)
    {
        $this->test = $test;
        $this->cases = $cases;
    }

    /**
     * @return string
     */
    public function getTest(): string
    {
        return $this->test;
    }

    /**
     * @return array
     */
    public function getCases(): array
    {
        return $this->cases;
    }
}
