<?php declare(strict_types=1);

namespace RcNetwork\Components\WebSocket\HttpRequest;

use \Ratchet\ConnectionInterface;
use \Ratchet\Http\HttpRequestParser as RatchetHttpRequestParser;

use \GuzzleHttp\Psr7\Message;

use \Psr\Http\Message\RequestInterface;

/**
 * Provides a parser for handling incoming WebSocket HTTP request data.
 *
 * @author Pihe Edmond <pihedy@gmail.com>
 */
class Parser extends RatchetHttpRequestParser
{
    /**
     * End of message marker.
     */
    public const EOM = "\r\n\r\n";

    /**
     * Stores the buffer of the incoming HTTP request data.
     */
    protected string $buffer = '';

    /**
     * Handles incoming WebSocket message data.
     *
     * @param ConnectionInterface   $Connection     The WebSocket connection interface.
     * @param string                $data           The incoming message data.
     */
    public function onMessage(ConnectionInterface $Connection, $data)
    {
        $this->buffer .= $data;

        if (strlen($this->buffer) > $this->maxSize) {
            throw new \OverflowException("Maximum buffer size of {$this->maxSize} exceeded parsing HTTP header");
        }

        if (!$this->isEndOfMessage($this->buffer)) {
            return;
        }

        $Request        = $this->headerParse($this->buffer);
        $this->buffer   = '';

        return $Request;
    }

    /**
     * Checks if the given message string represents the end of an HTTP request.
     *
     * @param string $message The message string to check.
     *
     * @return bool True if the message represents the end of an HTTP request, false otherwise.
     */
    public function isEndOfMessage(string $message): bool
    {
        return str_ends_with($message, self::EOM);
    }

    /**
     * Parses the provided HTTP headers and returns a PSR-7 RequestInterface object.
     *
     * @param string $headers The HTTP headers to parse.
     *
     * @return RequestInterface The parsed HTTP request.
     */
    public function headerParse(string $headers): RequestInterface
    {
        return Message::parseRequest($headers);
    }
}
