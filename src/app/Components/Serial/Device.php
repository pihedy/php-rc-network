<?php declare(strict_types=1);

namespace RcNetwork\Components\Serial;

class Device implements DeviceInterface
{
    protected ?string $port = null;

    public static function init(Serial $Serial): self
    {
        $Self = new self($Serial);

        $Self->open();

        return $Self;
    }

    public function __construct(protected Serial $Serial)
    {
        /* Do Nothing */
    }

    public function open(): void
    {
        if (!$this->hasPort()) {
            $this->setPort($this->Serial->getProp('port', ''));
        }
    }

    public function getPort(): ?string
    {
        return $this->port;
    }

    public function setPort(string $port): void
    {
        if ($this->isOpened()) {
            throw new \Exception('Device already opened.');
        }

        if (preg_match("@^COM(\\d+):?$@i", $port, $output)) {
            $port = sprintf('/dev/ttyS%d', $output[1] - 1);
        }

        if ($this->Serial->exec("stty -F {$port} 2>&1") !== 0) {
            throw new \Exception("Failed to set device: {$port}");
        }

        $this->port = $port;
    }

    public function hasPort(): bool
    {
        return $this->port !== null;
    }

    public function isOpened(): bool
    {
        return false;
    }
}

