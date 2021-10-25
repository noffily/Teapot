<?php

declare(strict_types=1);

namespace Noffily\Teapot\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD|Attribute::IS_REPEATABLE)]
class Depends
{
    private string $on;

    public function __construct(string $on)
    {
        $this->on = $on;
    }

    public function getOn(): string
    {
        return $this->on;
    }
}
