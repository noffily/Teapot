<?php

declare(strict_types=1);

namespace Noffily\Teapot\Data;

final class TestCase
{
    private string $test;
    private ?string $case;
    private array $depends;

    public function __construct(string $test, ?string $case, array $depends = [])
    {
        $this->test = $test;
        $this->case = $case;
        $this->depends = $depends;
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
    public function getCase(): ?string
    {
        return $this->case;
    }

    public function __toString(): string
    {
        return $this->getTest() . '::' . $this->getCase();
    }
}
