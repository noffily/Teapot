<?php

namespace Noffily\Teapot\Core;

use SebastianBergmann\FileIterator\Facade;

class Loader
{
    private array $files;

    public function __construct(string $directory, string $prefix = '', string $suffix = '.php', array $exclude = [])
    {
        $this->files = (new Facade())->getFilesAsArray($directory, $suffix, $prefix, $exclude);
    }
}