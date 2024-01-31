<?php declare(strict_types=1);

namespace RcNetwork\Component\Console\Input;

abstract class AbstractInput implements InputInterface
{
    protected array $data = [];

    protected array $arguments = [];

    abstract protected function parse(): void;
}
