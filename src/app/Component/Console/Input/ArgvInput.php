<?php declare(strict_types=1);

namespace RcNetwork\Component\Console\Input;

class ArgvInput extends AbstractInput
{
    public function __construct(?array $argv = null)
    {
        $this->data ??= $argv ?? $_SERVER['argv'] ?? [];
    }

    protected function parse(): void
    {
        array_shift($this->data);
    }
}
