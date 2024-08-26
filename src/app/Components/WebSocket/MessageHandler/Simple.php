<?php declare(strict_types=1);

namespace RcNetwork\Components\WebSocket\MessageHandler;

use \Ratchet\ {ConnectionInterface, MessageComponentInterface};

use \Symfony\Component\Console\Output\OutputInterface;

/**
 * Implements the `MessageComponentInterface` to handle WebSocket messages in a simple way.
 *
 * @author Pihe Edmond <pihedy@gmail.com>
 */
class Simple implements MessageComponentInterface
{
    /**
     * Initializes a new instance of the `Simple` class with the specified output interface.
     *
     * @param OutputInterface $Output The output interface to use for logging.
     *
     * @return self A new instance of the `Simple` class.
     */
    public static function init(OutputInterface $Output): self
    {
        return new self(new \SplObjectStorage(), $Output);
    }

    /**
     * Initializes a new instance of the `Simple` class with the specified output interface.
     *
     * @param \SplObjectStorage $Storage    The storage object to use for managing connections.
     * @param OutputInterface   $Output     The output interface to use for logging.
     */
    public function __construct(protected \SplObjectStorage $Storage, protected OutputInterface $Output)
    {
        /* Do Nothing */
    }

    /**
     * Handles the opening of a new WebSocket connection.
     *
     * @param ConnectionInterface $Connection The new WebSocket connection.
     */
    public function onOpen(ConnectionInterface $Connection): void
    {
        $this->Storage->attach($Connection);
    }

    /**
     * Handles the receipt of a message from a WebSocket connection.
     *
     * @param ConnectionInterface $From     The WebSocket connection that sent the message.
     * @param string              $message  The message received from the WebSocket connection.
     */
    public function onMessage(ConnectionInterface $From, $message): void
    {
        foreach ($this->Storage as $Connection) {
            if ($Connection === $From) {
                continue;
            }

            $Connection->send($message);
        }

        $this->Output->writeln("<info>Received message from {$From->resourceId}: {$message}</info>");
    }

    /**
     * Handles the closing of a WebSocket connection.
     *
     * @param ConnectionInterface $Connection The WebSocket connection that is being closed.
     */
    public function onClose(ConnectionInterface $Connection): void
    {
        $this->Storage->detach($Connection);
    }

    /**
     * Handles an error that occurred during a WebSocket connection.
     *
     * @param ConnectionInterface   $Connection     The WebSocket connection that encountered the error.
     * @param \Throwable            $Exception      The exception that was thrown.
     */
    public function onError(ConnectionInterface $Connection, \Throwable $Exception): void
    {
        $this->Output->writeln("<error>Error: {$Exception->getMessage()}</error>");
    }
}
