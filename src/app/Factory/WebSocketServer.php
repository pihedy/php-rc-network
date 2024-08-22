<?php declare(strict_types=1);

namespace RcNetwork\Factory;

use \RcNetwork\Components\WebSocket\Server;
use \RcNetwork\Components\WebSocket\MessageHandler\Simple;
use \RcNetwork\Components\WebSocket\HttpRequest\ {Handler, Parser};

use \Ratchet\MessageComponentInterface;
use \Ratchet\WebSocket\WsServer;
use \React\EventLoop\Loop;
use \React\Socket\SocketServer;

use \Symfony\Component\Console\Output\OutputInterface;

/**
 * WebSocketServerFactory is a factory class that creates a WebSocket server instance.
 *
 * @author Pihe Edmond <pihedy@gmail.com>
 */
final class WebSocketServer
{
    /**
     * Creates a simple WebSocket server instance.
     *
     * @param OutputInterface $Output The output interface to use for the server.
     *
     * @return Server The created WebSocket server instance.
     */
    public static function createSimple(OutputInterface $Output): Server
    {
        return self::createByMessageHandler(Simple::init($Output));
    }

    /**
     * Creates a WebSocket server instance using the provided message handler.
     *
     * @param MessageComponentInterface $MessageHandler     The message handler to use for the WebSocket server.
     * @param string                    $uri                The URI to bind the WebSocket server to (default: '0.0.0.0:3000').
     *
     * @return Server The created WebSocket server instance.
     */
    public static function createByMessageHandler(MessageComponentInterface $MessageHandler, string $uri = '0.0.0.0:3000'): Server
    {
        $Loop = Loop::get();

        $SocketServer   = new SocketServer($uri, [], $Loop);
        $HttpServer     = new Handler(
            new WsServer($MessageHandler),
            new Parser()
        );

        return new Server($HttpServer, $SocketServer, $Loop);
    }
}
