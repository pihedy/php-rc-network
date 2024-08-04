<?php declare(strict_types=1);

namespace RcNetwork\Command;

use \Symfony\Component\Console\Command\Command;

use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;

use \Ratchet\App as RatchetApp;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use \RcNetwork\Component\Connection\Server;
use \RcNetwork\Component\Serial\Serial;

class ControlStartCommand extends Command
{
    /**
     * Executes the control start command.
     *
     * @param InputInterface    $Input      The input interface.
     * @param OutputInterface   $Output     The output interface.
     *
     * @return int The exit status code.
     */
    protected function execute(InputInterface $Input, OutputInterface $Output): int
    {
        $result = self::SUCCESS;

        try {
            $Output->writeln('<info>Start the control process.</info>');

            $Serial = new Serial();

            $Serial->deviceSet('/dev/serial0');
            $Serial->confBaudRate(9600);
            $Serial->confParity('none');
            $Serial->confCharacterLength(8);
            $Serial->confStopBits(1);
            $Serial->confFlowControl('none');

            $status = $Serial->deviceOpen();

            if ($status === false) {
                throw new \Exception('Failed to open the serial port.');
            }

            $Server = Server::init($Serial, $Output);

            $RatchetApp = IoServer::factory(
                new HttpServer(new WsServer($Server)),
                3000
            );

            $RatchetApp->run();
           
            /* $RatchetApp = new RatchetApp('0.0.0.0', 8080, '0.0.0.0');
            $RatchetApp->route('/control', $Server, ['*']);
            $RatchetApp->run(); */
        } catch (\Throwable $e) {
            $Output->writeln('<error>'. $e->getMessage(). '</error>');

            $result = self::FAILURE;
        }

        return $result;
    }

    /**
     * Configures the command by setting its description.
     */
    protected function configure(): void
    {
        $this->setDescription('Start the control process.');
    }
}
