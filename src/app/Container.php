<?php declare(strict_types=1);

namespace RcNetwork;

use \Psr\Container\ContainerInterface;

/**
 * Container class that implements PSR-11 ContainerInterface. 
 * Provides methods to set, get and check for services.
 * 
 * @author Pihe Edmond <pihedy@gmail.com>
 */
class Container implements ContainerInterface
{
    /**
     * @var array An array to store the services registered in the container.
     */
    protected array $services = [];

    /**
     * @var string Separator string used to delimit service name segments.
     */
    protected string $separator = '.';

    /**
     * @var array An array to cache the last retrieved service.
     */
    protected array $cache = ['key' => null, 'value' => null];

    /**
     * Gets a service from the container.
     *
     * Checks if the service is cached first before retrieving it. 
     * Falls back to traversing the services array to find the service.
     *
     * @param string    $key        The key of the service to retrieve.
     * @param mixed     $default    Default value to return if service not found.
     * 
     * @return mixed The service if found, default value if not found.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        if ($this->cache['key'] == $key && $this->cache['content'] !== null) {
            return $this->cache['content'];
        }

        if (isset($this->services[$key])) {
            return $this->services[$key];
        }

        $segments   = explode($this->separator, $key);
        $root       = $this->services;

        foreach ($segments as $segment) {
            if (!isset($root[$segment])) {
                $root = $default;

                break;
            }

            $root = $root[$segment];
        }

        return $root;
    }

    /**
     * Checks if a service is registered in the container.
     *
     * @param string $key The key of the service to check for.
     * 
     * @return bool True if the service is registered, false otherwise.
     */
    public function has(string $key): bool
    {
        $content = $this->get($key);

        if ($content === null) {
            return false;
        }

        $this->cache = [
            'key'       => $key,
            'content'   => $content
        ];

        return true;
    }

    /**
     * Sets a service in the container.
     *
     * @param string    $key        The key to associate the service with. 
     * @param mixed     $service    The service object or factory function.
     */
    public function set(string $key, mixed $service): void
    {
        if (is_callable($service)) {
            $service = $service($this);
        }

        $this->services[$key] = $service;
    }

    /**
     * Removes a service from the container.
     *
     * @param string $key The key of the service to remove.
     */
    public function remove(string $key): void
    {
        if (!$this->has($key)) {
            return;
        }

        unset($this->services[$key]);
    }
}
