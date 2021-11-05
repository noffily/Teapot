<?php

declare(strict_types=1);

namespace Noffily\Teapot\Core;

final class CliOutput
{
    private const ESCAPE = "\033";
    private const BACKSPACE = "\x08";

    private const RESET = '0';
    private const RED_FG = '1;31';
    private const GREEN_FG = '1;32';
    private const YELLOW_FG = '1;36';
    private const CYAN_FG = '1;36';

    private const PROCESS_SYMBOL = '→';
    private const PASSED_SYMBOL = '✔';
    private const FAILED_SYMBOL = '✘';
    private const SKIPPED_SYMBOL = '↩';
    private const INCOMPLETE_SYMBOL = '∅';

    private int $outputCount = 0;
    private bool $colorsEnabled;

    public function __construct(bool $colorsEnabled = true)
    {
        $this->colorsEnabled = $colorsEnabled;
    }

    public function text(string $text): self
    {
        $this->outputCount += strlen($text);
        return $this->output($text);
    }

    public function backspace(int $times): self
    {
        return $this->output(str_repeat(self::BACKSPACE, $times));
    }

    public function newLine(): self
    {
        return $this->output(PHP_EOL);
    }

    public function reset(): self
    {
        return $this->outputColor(self::RESET);
    }

    public function red(): self
    {
        return $this->outputColor(self::RED_FG);
    }

    public function green(): self
    {
        return $this->outputColor(self::GREEN_FG);
    }

    public function yellow(): self
    {
        return $this->outputColor(self::YELLOW_FG);
    }

    public function cyan(): self
    {
        return $this->outputColor(self::CYAN_FG);
    }

    public function progress(): self
    {
        ++$this->outputCount;
        return $this->output(self::PROCESS_SYMBOL);
    }

    public function passed(): self
    {
        ++$this->outputCount;
        return $this->output(self::PASSED_SYMBOL);
    }

    public function failed(): self
    {
        ++$this->outputCount;
        return $this->output(self::FAILED_SYMBOL);
    }

    public function skipped(): self
    {
        ++$this->outputCount;
        return $this->output(self::SKIPPED_SYMBOL);
    }

    public function incomplete(): self
    {
        ++$this->outputCount;
        return $this->output(self::INCOMPLETE_SYMBOL);
    }

    public function count(): int
    {
        return $this->outputCount;
    }

    public function resetCount(): self
    {
        $this->outputCount = 0;
        return $this;
    }

    protected function outputColor(string $color): self
    {
        if (!$this->colorsEnabled) {
            return $this;
        }
        return $this->output(self::ESCAPE . '[' . $color . 'm');
    }

    protected function output(string $text): self
    {
        echo $text;
        return $this;
    }
}
