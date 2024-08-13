<?php declare(strict_types=1);

namespace RcNetwork\Components\Serial;

/**
 * Defines an abstract base class for serial devices.
 * Provides common functionality and properties for serial device implementations.
 *
 * @author Pihe Edmond <pihedy@gmail.com>
 */
abstract class AbstractDevice implements DeviceInterface
{
    /**
     * A list of common baud rates used for serial communication.
     *
     * @var int[]
     */
    public const BAUD_RATES = [
        110, 150, 300, 600, 1200,
        2400, 4800, 9600, 19200, 38400,
        57600, 115200, 230400, 460800, 500000,
        576000, 921600, 1000000, 1152000, 1500000,
        2000000, 2500000, 3000000, 3500000, 4000000
    ];

    /**
     * Constructs a new instance of the AbstractDevice class with the provided properties.
     *
     * @param array $props An associative array of properties for the device.
     */
    public function __construct(protected array $props)
    {
        /* Do Nothing */
    }

    /**
     * Gets the value of the specified property, or a default value if the property does not exist.
     *
     * @param string    $key        The name of the property to retrieve.
     * @param mixed     $default    The default value to return if the property does not exist.
     *
     * @return mixed The value of the specified property, or the default value if it does not exist.
     */
    public function getProp(string $key, mixed $default = null): mixed
    {
        return $this->props[$key] ?? $default;
    }

    /**
     * Checks if the specified property exists in the device's properties.
     *
     * @param string $key The name of the property to check.
     *
     * @return bool True if the property exists, false otherwise.
     */
    public function hasProp(string $key): bool
    {
        return isset($this->props[$key]);
    }
}
