<?php

declare(strict_types=1);

namespace Noffily\Teapot\Data;

final class Config
{
    private string $directory;
    private string $prefix;
    private string $suffix;
    private array $exclude;

    /**
     * @param string $directory
     * @param string $prefix
     * @param string $suffix
     * @param array $exclude
     */
    public function __construct(string $directory, string $prefix = '', string $suffix = '.php', array $exclude = [])
    {
        $this->directory = $directory;
        $this->prefix = $prefix;
        $this->suffix = $suffix;
        $this->exclude = $exclude;
    }

    /**
     * @return string
     */
    public function getDirectory(): string
    {
        return $this->directory;
    }

    /**
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * @return string
     */
    public function getSuffix(): string
    {
        return $this->suffix;
    }

    /**
     * @return array
     */
    public function getExclude(): array
    {
        return $this->exclude;
    }
}
