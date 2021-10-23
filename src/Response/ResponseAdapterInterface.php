<?php

declare(strict_types=1);

namespace Noffily\Teapot\Response;

use Symfony\Component\HttpFoundation\Response;

interface ResponseAdapterInterface
{
    public function adapt(): Response;
}
