<?php

declare(strict_types=1);

namespace Noffily\Teapot\Request;

use Symfony\Component\HttpFoundation\Request;

interface RequestAdapterInterface
{
    public function adapt(): Request;
}
