<?php declare(strict_types=1);

namespace RcNetwork\Components\Serial;

abstract class AbstractDevice implements DeviceInterface
{
    public function __construct(protected array $props)
    {
        /* Do Nothing */
    }

    public function getProp(string $key, mixed $default = null): mixed
    {
        return $this->props[$key] ?? $default;
    }
}
