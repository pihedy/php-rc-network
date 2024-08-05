<?php declare(strict_types=1);

namespace RcNetwork\Components\Serial;

interface SerialInterface
{
    public function exec(string $command): int;

    public function open(): self;

    public function write(): void;

    public function read(): void;

    public function close(): void;
}
