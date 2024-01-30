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
     * An array to store the services registered in the container.
     */
    private array $services = [];

    /**
     * Gets a service from the container. 
     * 
     * @param string $id The service ID to retrieve.
     * 
     * @return mixed The service instance.
     * 
     * @throws \InvalidArgumentException If the service does not exist.
     */
    public function get(string $id): mixed
    {
        if (!$this->has($id)) {
            throw new \InvalidArgumentException("Service $id not found");
        }

        return $this->services[$id];
    }

    /**
     * Checks if a service exists in the container.
     *
     * @param string $id The service ID to check for.
     *
     * @return bool True if the service exists, false otherwise.
     */
    public function has(string $id): bool
    {
        return isset($this->services[$id]);
    }

    /**
     * Sets a service in the container.
     *
     * @param string    $id         The service ID. 
     * @param mixed     $service    The service instance.
     */
    public function set(string $id, mixed $service): void
    {
        $this->services[$id] = $service;
    }
}
