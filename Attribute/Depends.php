<?php

declare(strict_types=1);

namespace Noffily\Teapot\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD|Attribute::IS_REPEATABLE)]
class Depends
{
    private string $test;
    private string $case;

    public function __construct(string $test, string $case)
    {
        $this->test = $test;
        $this->case = $case;
    }

    public function getTest(): string
    {
        return $this->test;
    }

    public function getCase(): string
    {
        return $this->case;
    }

    public function __toString(): string
    {
        return $this->test . '::' . $this->case;
    }
}
