<?php declare(strict_types=1);

namespace RcNetwork\Components\Serial;

/**
 * Defines the interface for a device that can be controlled via a serial connection.
 * This interface provides methods for building and retrieving the setup command,
 * getting and setting the port, and checking the opened state of the device.
 *
 * @author Pihe Edmond <pihedy@gmail.com>
 */
interface DeviceInterface
{
    /**
     * Builds the setup command for the device.
     *
     * @param string $glue The glue string to use between the command parts.
     */
    public function buildSetupCommand(string $glue = ' '): void;

    /**
     * Builds and returns the setup command for the device.
     *
     * @param string $glue The glue string to use between the command parts.
     *
     * @return string The setup command for the device.
     */
    public function getSetupCommand(): string;

    /**
     * Gets the port associated with the device.
     *
     * @return null|string The port associated with the device, or null if no port is set.
     */
    public function getPort(): ?string;

    /**
     * Sets the port associated with the device.
     *
     * @param string $port The port to associate with the device.
     */
    public function setPort(string $port): void;

    /**
     * Sets the opened state of the device.
     *
     * @param bool $opened Whether the device is opened or not.
     */
    public function setOpened(bool $opened): void;

    /**
     * Checks if the device has a port associated with it.
     *
     * @return bool True if the device has a port, false otherwise.
     */
    public function hasPort(): bool;

    /**
     * Checks if the device is opened.
     *
     * @return bool True if the device is opened, false otherwise.
     */
    public function isOpened(): bool;
}
