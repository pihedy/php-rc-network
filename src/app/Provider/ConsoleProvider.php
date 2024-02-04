<?php declare(strict_types=1);

namespace RcNetwork\Provider;

use \RcNetwork\Interface\ProviderInterface;

use \RcNetwork\App;
use \Symfony\Component\Console\Application;

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
        $App->set('console', function (App $App) {
            $ConsoleApplication = new Application(
                $App->config->get('app.name'),
                $App->config->get('app.version')
            );

            $ConsoleApplication->addCommands($App->config->get('commands.list'));

            return $ConsoleApplication;
        });
    }
}
