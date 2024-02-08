<?php declare(strict_types=1);

namespace RcNetwork\Command;

use \Symfony\Component\Console\Command\Command;

use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;

class TestCommand extends Command
{
    protected function execute(InputInterface $Input, OutputInterface $Output): int
    {
        $Output->writeln('Hello World!');

        return self::FAILURE;
    }
}

