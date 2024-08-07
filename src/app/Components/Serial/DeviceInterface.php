<?php declare(strict_types=1);

namespace RcNetwork\Components\Serial;

interface DeviceInterface
{
    public function getPort(): ?string;

    public function setPort(string $port): void;

    public function setOpened(bool $opened): void;

    public function hasPort(): bool;

    public function isOpened(): bool;

    public function buildCommand(string $glue): string;
}
