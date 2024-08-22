<?php declare(strict_types=1);

namespace RcNetwork\Components\WebSocket\HttpRequest;

use \Ratchet\ {MessageComponentInterface, ConnectionInterface};

use \Ratchet\Http\ {HttpServerInterface, HttpRequestParser};

use \GuzzleHttp\Psr7\Response;

/**
 * Implements the `MessageComponentInterface` to handle WebSocket connections and messages.
 *
 * @author Pihe Edmond <pihedy@gmail.com>
 */
class Handler implements MessageComponentInterface
{
    /**
     * Indicates whether a WebSocket message has been received from the client.
     */
    protected bool $hasReceived = false;

    /**
     * Constructs a new instance of the `Handler` class, which implements the `MessageComponentInterface`.
     *
     * @param HttpServerInterface   $HttpServer         The HTTP server interface implementation.
     * @param HttpRequestParser     $HttpRequestParser  The HTTP request parser implementation.
     */
    public function __construct(
        public readonly HttpServerInterface $HttpServer,
        public readonly HttpRequestParser $HttpRequestParser
    ) {
        /* Do Nothing */
    }

    /**
     * Handles the opening of a WebSocket connection.
     *
     * @param ConnectionInterface $Connection The WebSocket connection interface.
     */
    public function onOpen(ConnectionInterface $Connection): void
    {
        $this->hasReceived = false;
    }

    /**
     * Handles a WebSocket message received from the client.
     *
     * @param ConnectionInterface   $Connection     The WebSocket connection interface.
     * @param mixed                 $data           The message data received from the client.
     */
    public function onMessage(ConnectionInterface $Connection, $data): void
    {
        try {
            if ($this->hasReceived) {
                $this->HttpServer->onMessage($Connection, $data);

                return;
            }

            $Request = $this->HttpRequestParser->onMessage($Connection, $data);

            if ($Request === null) {
                return;
            }

            $this->hasReceived = true;

            $this->HttpServer->onOpen($Connection, $Request);
        } catch (\OverflowException) {
            $this->close($Connection, 413);
        }
    }

    /**
     * Handles the closing of a WebSocket connection.
     *
     * @param ConnectionInterface $Connection The WebSocket connection interface.
     */
    public function onClose(ConnectionInterface $Connection): void
    {
        if (!$this->hasReceived) {
            return;
        }

        $this->HttpServer->onClose($Connection);
    }

    /**
     * Handles an error that occurred during a WebSocket connection.
     *
     * @param ConnectionInterface   $Connection     The WebSocket connection interface.
     * @param \Throwable            $Exception      The exception that occurred.
     */
    public function onError(ConnectionInterface $Connection, \Throwable $Exception): void
    {
        if (!$this->hasReceived) {
            $this->close($Connection, 500);

            return;
        }

        $this->HttpServer->onError($Connection, $Exception);
    }

    /**
     * Closes the WebSocket connection with the specified status code and headers.
     *
     * @param ConnectionInterface   $Connection     The WebSocket connection interface.
     * @param int                   $code           The status code to use when closing the connection.
     * @param array                 $headers        Additional headers to include in the close response.
     */
    protected function close(ConnectionInterface $Connection, int $code = 400, array $headers = []): void
    {
        $Response = new Response($code, $headers);

        $Connection->send((string) $Response);
        $Connection->close();
    }
}
