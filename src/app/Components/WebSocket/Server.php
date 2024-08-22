<?php declare(strict_types=1);

namespace RcNetwork\Components\WebSocket;

use \RcNetwork\Interface\WebSocketServerInterface;

use \Ratchet\MessageComponentInterface;

use \React\Socket\ {SocketServer, Connection};
use \React\EventLoop\ {LoopInterface, Loop};

use \RcNetwork\Components\WebSocket\Connection as WebSocketConnection;

/**
 * Implements the WebSocketServerInterface to provide a WebSocket server.
 *
 * @author Pihe Edmond <pihedy@gmail.com>
 */
class Server implements WebSocketServerInterface
{
    /**
     * Stores the current WebSocket connection.
     */
    protected null|WebSocketConnection $WebSocketConnection = null;

    /**
     * Constructs a new instance of the WebSocket server.
     *
     * @param MessageComponentInterface     $MessageComponent   The message component to handle WebSocket events.
     * @param SocketServer                  $SocketServer       The socket server to handle incoming WebSocket connections.
     * @param null|LoopInterface            $Loop               The event loop to use for the WebSocket server.
     */
    public function __construct(
        protected MessageComponentInterface $MessageComponent,
        protected SocketServer $SocketServer,
        protected null|LoopInterface $Loop = null
    ) {
        if ($this->Loop === null) {
            $this->Loop = Loop::get();
        }

        $this->SocketServer->on('connection', [$this, 'handleConnect']);
    }

    /**
     * Runs the WebSocket server's event loop.
     */
    public function run(): void
    {
        $this->Loop->run();
    }

    /**
     * Handles a new WebSocket connection.
     *
     * @param Connection $Connection The incoming WebSocket connection.
     */
    public function handleConnect(Connection $Connection): void
    {
        $address = $Connection->getRemoteAddress();
        $address = trim(
            parse_url(
                (strpos($address, '://') === false ? 'tcp://' : '') . $address, PHP_URL_HOST
            ), '[]'
        );

        $this->WebSocketConnection = new WebSocketConnection(
            $Connection,
            (int) $Connection->stream,
            $address
        );

        $this->MessageComponent->onOpen($this->WebSocketConnection);

        $Connection->on('data', function ($data) {
            $this->MessageComponent->onMessage($this->WebSocketConnection, $data);
        });

        $Connection->on('close', function () {
            $this->MessageComponent->onClose($this->WebSocketConnection);
        });

        $Connection->on('error', function (\Throwable $Exception) {
            $this->MessageComponent->onError($this->WebSocketConnection, $Exception);
        });
    }
}
