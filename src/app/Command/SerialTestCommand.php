<?php declare(strict_types=1);

namespace RcNetwork\Command;

use \Symfony\Component\Console\Command\Command;

use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;

use \RcNetwork\Component\Serial\Serial;
use \Symfony\Component\Console\Input\InputArgument;

class SerialTestCommand extends Command
{
    protected function execute(InputInterface $Input, OutputInterface $Output): int
    {
        $Serial = new Serial();

        $Output->writeln('Set device to /dev/serial0');

        $Serial->deviceSet('/dev/serial0');

        
        $Serial->confBaudRate(9600);
        $Serial->confParity('none');
        $Serial->confCharacterLength(8);
        $Serial->confStopBits(1);
        $Serial->confFlowControl('none');
        
        $Output->writeln('Open device /dev/serial0 with 9600,8,N,1.');

        $Serial->deviceOpen();

        $Output->writeln('Send message to device.');

        $Serial->sendMessage($Input->getArgument('message'));

        $hasMessage = false;

        do {
            $response = $Serial->readPort(1);

            if ($response == '') {
                continue;
            }

            $Output->writeln($response);

            $hasMessage = true;
        } while (!$hasMessage);

        $Serial->deviceClose();

        $Output->writeln('Close.');

        return self::SUCCESS;
    }

    protected function configure(): void
    {
        $this->setName('serial:test')
            ->setDescription('Test serial port')
            ->addArgument('message', InputArgument::REQUIRED, 'Message to send');
    }
}
