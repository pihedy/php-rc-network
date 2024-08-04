<?php declare(strict_types=1);

namespace RcNetwork\Components\Config;

use \RcNetwork\Container;

/**
 * PhpFileConfig class extends Container.
 * Provides configuration loading from PHP files.
 * 
 * @author Pihe Edmond <pihedy@gmail.com>
 */
class PhpFileConfig extends Container
{
    /**
     * Constructor method for the Container class. 
     * Initializes the services property with the provided array of services.
     * 
     * @param array $services Array of services to initialize the container with.
     */
    public function __construct(array $services = [])
    {
        $this->services = $services;
    }
}
