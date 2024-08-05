<?php declare(strict_types=1);

namespace RcNetwork\Components\Serial;

class LinuxDevice extends AbstractDevice
{
    protected ?string $port = null;

    protected int $boudrate = 9600;

    protected bool $opened = false;

    public function getPort(): string
    {
        if (!$this->hasPort()) {
            $this->setPort($this->getProp('port', ''));
        }

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

        $this->port = $port;
    }

    public function setOpened(bool $opened): void
    {
        $this->opened = $opened;
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

