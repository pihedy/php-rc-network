<?php declare(strict_types=1);

namespace RcNetwork\Command\Test;

use \Symfony\Component\Console\ {
    Command\Command,
    Input\InputInterface,
    Output\OutputInterface
};

use \RcNetwork\Factory\WebSocketServer as WebSocketServerFactory;

/**
 * WebSocketServer is a console command that starts a WebSocket server.
 *
 * @author Pihe Edmond <pihedy@gmail.com>
 */
class WebSocketServer extends Command
{
    /**
     * Executes the WebSocket server command.
     *
     * @param InputInterface    $Input      The input interface.
     * @param OutputInterface   $Output     The output interface.
     *
     * @return int The exit status code.
     */
    protected function execute(InputInterface $Input, OutputInterface $Output): int
    {
        WebSocketServerFactory::createSimple($Output)->run();

        return self::SUCCESS;
    }
}
