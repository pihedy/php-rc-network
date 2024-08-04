<?php declare(strict_types=1);

namespace RcNetwork\Component\Connection;

use \Ratchet\ {MessageComponentInterface, ConnectionInterface};

use \Symfony\Component\Console\Output\OutputInterface;

use \RcNetwork\Component\Serial\Serial;

class Server implements MessageComponentInterface
{
    public static function init(Serial $Serial, OutputInterface $Output): self
    {
        return new self(new \SplObjectStorage(), $Serial, $Output);
    }

    public function __construct(protected \SplObjectStorage $Storage, protected Serial $Serial, protected OutputInterface $Output)
    {
        /* Do Nothing. */
    }

    public function onOpen(ConnectionInterface $Connection): void
    {
        $this->Storage->attach($Connection);
    }

    public function onMessage(ConnectionInterface $From, $message): void
    {
        $this->Output->writeln("<info>Received message from {$From->resourceId}: {$message}</info>");

        $this->Serial->sendMessage($message);
    }

    public function onClose(ConnectionInterface $Connection): void
    {
        $this->Storage->detach($Connection);
    }

    public function onError(ConnectionInterface $Connection, \Exception $Exception): void
    {
        $Connection->close();
    }
}
