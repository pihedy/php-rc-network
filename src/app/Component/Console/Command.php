<?php declare(strict_types=1);

namespace RcNetwork\Component\Console;

use \RcNetwork\Component\Console\Input\InputInterface;

abstract class Command
{
    abstract protected function execute(InputInterface $Input, OutputInterface $Output): int;

    public function isEnabled(): bool
    {
        return true;
    }
}
