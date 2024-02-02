<?php declare(strict_types=1);

namespace RcNetwork;

use \RcNetwork\Component\Config\PhpFileLoader;
use \RcNetwork\Interface\ProviderInterface;

use \Symfony\Component\Config\FileLocator;
use \Symfony\Component\Console\Application;

/**
 * The App class is a singleton that extends Container and provides application initialization and running logic.
 * 
 * It contains a private static property to hold the singleton instance, 
 * and a public static method getInstance() to retrieve the instance. 
 * The run() method contains the application initialization and running logic.
 * 
 * @author Pihe Edmond <pihedy@gmail.com>
 */
final class App extends Container
{
    /**
     * The singleton instance of the App class.
     * 
     * @var null|self The singleton instance of the App class.
     */
    private static ?self $Instance = null;

    /**
     * An array to hold ProviderInterface instances.
     * 
     * @var \RcNetwork\Interface\ProviderInterface[] An array to hold ProviderInterface instances.
     */
    private array $providers = [];

    /**
     * Gets the singleton instance of the App class.
     *
     * @return self Returns the singleton instance of the App class.
     */
    public static function getInstance(): self
    {
        if (self::$Instance === null) {
            self::$Instance = new self();
        }

        return self::$Instance;
    }

    private function bootstrapConfig(): void
    {
        $this->set('config', function () {
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

            return new Container($content);
        });
    }

    public function addProvider(ProviderInterface $Provider): void
    {
        $this->providers[] = $Provider;
    }

    public function addProviders(array $providers): void
    {
        $this->providers = array_merge($this->providers, $providers);
    }

    public function run(): void
    {
        $this->bootstrapConfig();
        $this->registerProviders();

        (new Application())->run();
    }

    private function registerProviders(): void
    {
        foreach ($this->providers as $Provider) {
            if (!$Provider instanceof ProviderInterface) {
                continue;
            }

            $Provider->register($this);
        }
    }
}
