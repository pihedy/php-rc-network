<?php declare(strict_types=1);

namespace RcNetwork\Provider;

use \RcNetwork\Interface\ProviderInterface;

use \RcNetwork\App;

use \Symfony\Component\Console\Application;
use \Symfony\Component\Console\Command\Command;

/**
 * ConsoleProvider implements the ProviderInterface.
 *
 * This class registers the Symfony Console Application as a service in the dependency injection container.
 * It sets up the application with the configured name, version, and console commands.
 *
 * @author Pihe Edmond <pihedy@gmail.com>
 */
class ConsoleProvider implements ProviderInterface
{
    /**
     * Registers the Symfony Console Application as a service in the App container.
     *
     * @param \RcNetwork\App $App The application instance.
     */
    public function register(App $App): void
    {
        $commands = $this->initCommands();

        $App->set('console', function (App $App) use ($commands) {
            $ConsoleApplication = new Application(
                $App->config->get('app.name'),
                $App->config->get('app.version')
            );

            $ConsoleApplication->addCommands($commands);

            return $ConsoleApplication;
        });
    }

    /**
     * Initializes the console commands.
     *
     * Gets the list of command class names from the application config.
     * Instantiates each command class and adds it to the commands array.
     * Validates that each command is an instance of Symfony\Component\Console\Command\Command.
     *
     * @return array An array of Symfony\Component\Console\Command\Command instances.
     */
    public function initCommands(): array
    {
        $commands = RcNetworkApp()->config->get('commands.list');

        foreach ($commands as $commandKey => $command) {
            /**
             * @var Command $Command
             */
            $Command = new $command($commandKey);

            if (!$Command instanceof Command) {
                throw new \Exception(
                    'Command ' . $commandKey . 'is not a valid command.'
                );
            }

            $commands[$commandKey] = $Command;
        }

        return $commands;
    }
}
