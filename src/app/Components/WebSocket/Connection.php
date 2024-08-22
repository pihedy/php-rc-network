<?php declare(strict_types=1);

namespace RcNetwork\Components\WebSocket;

use \Ratchet\ConnectionInterface;

use \React\Socket\Connection as ReactConnection;

/**
 * Represents a WebSocket connection.
 *
 * @author Pihe Edmond <pihedy@gmail.com>
 */
class Connection implements ConnectionInterface
{
    public $httpRequest;

    public $WebSocket;

    /**
     * Constructs a new `Connection` instance.
     *
     * @param ReactConnection   $Connection     The underlying React socket connection.
     * @param int               $resourceId     The unique identifier for this connection.
     * @param string            $remoteAddress  The remote address of the connected client.
     */
    public function __construct(
        public readonly ReactConnection $Connection,
        public readonly int $resourceId,
        public readonly string $remoteAddress
    ) {
        /* Do Nothing */
    }

    /**
     * Sends the provided data through the WebSocket connection.
     *
     * @param mixed $data The data to be sent.
     *
     * @return $this The current `Connection` instance.
     */
    public function send($data): self
    {
        $this->Connection->write($data);

        return $this;
    }

    /**
     * Closes the WebSocket connection.
     */
    public function close(): void
    {
        $this->Connection->end();
    }
}
