<?php declare(strict_types=1);

namespace RcNetwork\Provider;

use \RcNetwork\Interface\ProviderInterface;

use \RcNetwork\App;
use \RcNetwork\Components\Config\PhpFileConfig;

/**
 * ConfigProvider class implements ProviderInterface.
 * Registers application config by iterating over config files.
 *
 * @author Pihe Edmond <pihedy@gmail.com>
 */
class ConfigProvider implements ProviderInterface
{
    /**
     * Registers application config by iterating over config files.
     *
     * @param \RcNetwork\App $App The application instance.
     */
    public function register(App $App): void
    {
        $App->set('config', function () {
            $DirectoryIterator  = new \DirectoryIterator(MAIN_DIR . DIRECTORY_SEPARATOR . 'config');
            $content            = [];

            foreach ($DirectoryIterator as $Element) {
                if ($Element->isDot() || $Element->isDir()) {
                    continue;
                }

                if ($Element->getExtension() != 'php') {
                    continue;
                }

                $key            = $Element->getBasename('.php');
                $content[$key]  = include $Element->getPathname();
            }

            return new PhpFileConfig($content);
        });
    }
}
