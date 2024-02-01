<?php declare(strict_types=1);

namespace RcNetwork;

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

    public function run(): void
    {
        (new Application())->run();
    }
}
