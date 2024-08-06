<?php declare(strict_types=1);

namespace RcNetwork\Components\Serial;

abstract class AbstractDevice implements DeviceInterface
{
    public const BAUD_RATES = [
        110, 150, 300, 600, 1200, 
        2400, 4800, 9600, 19200, 38400, 
        57600, 115200, 230400, 460800, 500000, 
        576000, 921600, 1000000, 1152000, 1500000, 
        2000000, 2500000, 3000000, 3500000, 4000000
    ];

    public function __construct(protected array $props)
    {
        /* Do Nothing */
    }

    public function getProp(string $key, mixed $default = null): mixed
    {
        return $this->props[$key] ?? $default;
    }

    public function hasProp(string $key): bool
    {
        return isset($this->props[$key]);
    }
}
