<?php declare(strict_types=1);

namespace RcNetwork\Interface;

use \React\Socket\Connection;

/**
 * Defines the interface for a WebSocket server implementation.
 *
 * @author Pihe Edmond <pihedy@gmail.com>
 */
interface WebSocketServerInterface
{
    /**
     * Runs the WebSocket server.
     */
    public function run(): void;

    /**
     * Handles a new WebSocket connection.
     *
     * @param Connection $Connection The new WebSocket connection.
     */
    public function handleConnect(Connection $Connection): void;
}
