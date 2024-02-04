<?php declare(strict_types=1);

namespace RcNetwork;

use \RcNetwork\Interface\ProviderInterface;
use \Symfony\Component\Console\Application;

/**
 * The App class is a singleton that extends Container and provides application initialization and running logic.
 * 
 * It contains a private static property to hold the singleton instance, 
 * and a public static method getInstance() to retrieve the instance. 
 * The run() method contains the application initialization and running logic.
 * 
 * @property \RcNetwork\Component\Config\PhpFileConfig $config The configuration container.
 * 
 * @author Pihe Edmond <pihedy@gmail.com>
 */
final class App extends Container
{
    /**
     * @var null|self The singleton instance of the App class.
     */
    private static ?self $Instance = null;

    /**
     * @var array The core providers to bootstrap the application.
     */
    private array $core = [
        \RcNetwork\Provider\ConfigProvider::class,
    ];

    /**
     * Magic getter to retrieve values from the container.
     *
     * @param string $name The name of the value to retrieve.
     * 
     * @return mixed The value if found, null otherwise.
     */
    public function __get(string $name)
    {
        if (!$this->has($name)) {
            return null;
        }

        return $this->get($name);
    }

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

    /**
     * Bootstraps the application by registering the core providers.
     */
    private function bootstrap(): void
    {
        $this->registerProviders($this->core);
    }

    /**
     * Runs the application by bootstrapping, registering providers, and running the application.
     */
    public function run(): void
    {
        $this->bootstrap();
        $this->registerProviders();

        (new Application(
            $this->config->get('default.name'),
            $this->config->get('default.version')
        ))->run();
    }

    /**
     * Registers the given providers or all providers if none provided.
     *
     * @param string[] $providers The providers to register.
     */
    private function registerProviders(array $providers = []): void
    {
        foreach ($providers as $provider) {
            if (!class_exists($provider)) {
                continue;
            }

            $Provider = new $provider();

            if (!$Provider instanceof ProviderInterface) {
                continue;
            }

            $Provider->register($this);
        }
    }
}
