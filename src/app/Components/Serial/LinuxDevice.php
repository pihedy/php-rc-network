<?php declare(strict_types=1);

namespace RcNetwork\Components\Serial;

use \RcNetwork\Components\Serial\Exception\DeviceAlreadyOpenedException;

/**
 * Represents a Linux serial device.
 *
 * This class provides methods to set and retrieve various properties of a serial connection,
 * such as the port, baud rate, parity, character size, stop bits, and flow control.
 * It also provides methods to check the state of the serial connection, such as whether it is opened or closed.
 *
 * The class extends the `AbstractDevice` class, which likely provides common functionality for serial devices.
 *
 * @author Pihe Edmond <pihedy@gmail.com>
 */
class LinuxDevice extends AbstractDevice
{
    /**
     * The serial port that the device is connected to.
     *
     * @var null|string
     */
    protected ?string $port = null;

    /**
     * The baud rate of the serial connection.
     *
     * @var int
     */
    protected int $baudrate = 0;

    /**
     * The parity setting for the serial connection.
     *
     * @var null|string
     */
    protected ?string $parity = null;

    /**
     * The character size of the serial connection.
     *
     * @var null|string
     */
    protected ?string $characterSize = null;

    /**
     * The stop bits setting for the serial connection.
     *
     * @var null|string
     */
    protected ?string $stopBits = null;

    /**
     * The flow control setting for the serial connection.
     *
     * @var null|string
     */
    protected ?string $flowControl = null;

    /**
     * Indicates whether the serial device is currently opened.
     *
     * @var bool
     */
    protected bool $opened = false;

    protected ?string $setupCommand = null;

    public function buildSetupCommand(string $glue = ' '): void
    {
        $command = array_filter([
            $this->getPort(),
            $this->getBaudrate(),
            $this->getCharacterSize(),
            $this->getStopBits(),
            $this->getParity(),
            $this->getFlowControl()
        ], fn (int|string $value): mixed => !empty($value));

        $this->setupCommand = sprintf('stty -F %s', implode($glue, $command));
    }

    public function getSetupCommand(): string
    {
        if ($this->setupCommand === null) {
            $this->buildSetupCommand();
        }

        return $this->setupCommand;
    }

    /**
     * Returns the serial port that the device is connected to.
     *
     * If the port has not been set,
     * it will be retrieved from the application configuration using the 'port' key,
     * and set as the default value.
     *
     * @return string The serial port that the device is connected to.
     */
    public function getPort(): string
    {
        if (!$this->hasPort()) {
            $this->setPort($this->getProp('port', ''));
        }

        return $this->port;
    }

    /**
     * Returns the baud rate of the serial connection.
     *
     * If the baud rate has not been set,
     * it will be retrieved from the application configuration using the 'baudrate' key,
     * and set as the default value.
     *
     * @return int The baud rate of the serial connection.
     */
    public function getBaudrate(): int
    {
        if (!$this->hasBaudrate()) {
            $this->setBaudrate($this->getProp('baudrate', 9600));
        }

        return $this->baudrate;
    }

    /**
     * Returns the parity setting for the serial connection.
     *
     * If the parity setting has not been set,
     * it will be retrieved from the application configuration using the 'parity' key,
     * and set as the default value.
     *
     * @return string The parity setting for the serial connection.
     */
    public function getParity(): string
    {
        if (!$this->hasParity()) {
            $this->setParity($this->getProp('parity', 'none'));
        }

        return $this->parity;
    }

    /**
     * Returns the character size of the serial connection.
     *
     * If the character size has not been set,
     * it will be retrieved from the application configuration using the 'character_size' key,
     * and set as the default value.
     *
     * @return int The character size of the serial connection.
     */
    public function getCharacterSize(): string
    {
        if (!$this->hasCharacterSize()) {
            $this->setCharacterSize($this->getProp('character_size', 8));
        }

        return $this->characterSize;
    }

    /**
     * Returns the number of stop bits for the serial connection.
     *
     * If the stop bits setting has not been set,
     * it will be retrieved from the application configuration using the 'stop_bits' key,
     * and set as the default value.
     *
     * @return string The number of stop bits for the serial connection.
     */
    public function getStopBits(): string
    {
        if (!$this->hasStopBits()) {
            $this->setStopBits($this->getProp('stop_bits', false));
        }

        return $this->stopBits;
    }

    /**
     * Returns the flow control setting for the serial connection.
     *
     * If the flow control setting has not been set,
     * it will be retrieved from the application configuration using the 'flow_control' key,
     * and set as the default value.
     *
     * @return string The flow control setting for the serial connection.
     */
    public function getFlowControl(): string
    {
        if (!$this->hasFlowControl()) {
            $this->setFlowControl($this->getProp('flow_control', 'none'));
        }

        return $this->flowControl;
    }

    /**
     * Sets the port for the serial connection.
     *
     * If the device is already opened, an exception will be thrown.
     *
     * @param string $port The port for the serial connection.
     *
     * @throws \Exception If the device is already opened.
     */
    public function setPort(string $port): void
    {
        if ($this->isOpened()) {
            throw new DeviceAlreadyOpenedException('Device already opened.');
        }

        if (preg_match("@^COM(\\d+):?$@i", $port, $output)) {
            $port = sprintf('/dev/ttyS%d', $output[1] - 1);
        }

        $this->port = $port;
    }

    /**
     * Sets the baud rate for the serial connection.
     *
     * If the device is already opened, an exception will be thrown.
     *
     * @param int $baudrate The baud rate for the serial connection.
     *
     * @throws \Exception If the device is already opened or the baud rate is invalid.
     */
    public function setBaudrate(int $baudrate): void
    {
        if ($this->isOpened()) {
            throw new DeviceAlreadyOpenedException('Device already opened.');
        }

        if (!in_array($baudrate, self::BAUD_RATES)) {
            throw new \Exception('Invalid baudrate.');
        }

        $this->baudrate = $baudrate;
    }

    /**
     * Sets the parity for the serial connection.
     *
     * If the device is already opened, an exception will be thrown.
     *
     * @param string $parity The parity for the serial connection. Can be 'none', 'even', or 'odd'.
     *
     * @throws \Exception If the device is already opened or the parity is invalid.
     */
    public function setParity(string $parity): void
    {
        if ($this->isOpened()) {
            throw new DeviceAlreadyOpenedException('Device already opened.');
        }

        $valid = [
            'none'  => '-parenb',
            'even'  => 'parenb -parodd',
            'odd'   => 'parenb parodd',
        ];

        $parity = strtolower($parity);

        if (!isset($valid[$parity])) {
            throw new \InvalidArgumentException('Invalid parity.');
        }

        $this->parity = $valid[$parity];
    }

    /**
     * Sets the character size for the serial connection.
     *
     * If the device is already opened, an exception will be thrown.
     *
     * @param int $size The character size for the serial connection. Must be between 5 and 8.
     *
     * @throws \Exception If the device is already opened or the character size is invalid.
     */
    public function setCharacterSize(int $size): void
    {
        if ($this->isOpened()) {
            throw new DeviceAlreadyOpenedException('Device already opened.');
        }

        if ($size < 5 || $size > 8) {
            throw new \Exception('Invalid character size.');
        }

        $this->characterSize = "cs{$size}";
    }

    /**
     * Sets the number of stop bits for the serial connection.
     *
     * If the device is already opened, an exception will be thrown.
     *
     * @param bool $hasStopBits Whether to use one or two stop bits. `true` for two stop bits, `false` for one stop bit.
     *
     * @throws \Exception If the device is already opened.
     */
    public function setStopBits(bool $hasStopBits): void
    {
        if ($this->isOpened()) {
            throw new DeviceAlreadyOpenedException('Device already opened.');
        }

        $bits = 'cstopb';

        if ($hasStopBits) {
            $bits = "-{$bits}";
        }

        $this->stopBits = $bits;
    }

    /**
     * Sets the flow control mode for the serial connection.
     *
     * If the device is already opened, an exception will be thrown.
     *
     * @param string $flowControl The flow control mode. Must be one of 'none', 'rts', 'cts', or 'rtscts'.
     *
     * @throws \Exception If the device is already opened or the flow control mode is invalid.
     */
    public function setFlowControl(string $flowControl): void
    {
        if ($this->isOpened()) {
            throw new DeviceAlreadyOpenedException('Device already opened.');
        }

        $valid = [
            'none'      => '',
            'rts'       => '-crtscts',
            'cts'       => '-ccts',
            'rtscts'    => '-crtscts',
        ];

        if (!isset($valid[$flowControl])) {
            throw new \Exception('Invalid flow control.');
        }

        $this->flowControl = $valid[$flowControl];
    }

    /**
     * Sets whether the serial device is opened or closed.
     *
     * If the device is already opened, an exception will be thrown.
     *
     * @param bool $opened Whether the device is opened or closed.
     *
     * @throws \Exception If the device is already opened.
     */
    public function setOpened(bool $opened): void
    {
        $this->opened = $opened;
    }

    /**
     * Checks if the serial port has been set.
     *
     * @return bool True if the port has been set, false otherwise.
     */
    public function hasPort(): bool
    {
        return $this->port !== null;
    }

    /**
     * Checks if the serial port baudrate has been set.
     *
     * @return bool True if the baudrate has been set, false otherwise.
     */
    public function hasBaudrate(): bool
    {
        return $this->baudrate !== 0;
    }

    /**
     * Checks if the serial port parity has been set.
     *
     * @return bool True if the parity has been set, false otherwise.
     */
    public function hasParity(): bool
    {
        return $this->parity !== null;
    }

    /**
     * Checks if the serial port character size has been set.
     *
     * @return bool True if the character size has been set, false otherwise.
     */
    public function hasCharacterSize(): bool
    {
        return $this->characterSize !== null;
    }

    /**
     * Checks if the serial port stop bits have been set.
     *
     * @return bool True if the stop bits have been set, false otherwise.
     */
    public function hasStopBits(): bool
    {
        return $this->stopBits !== null;
    }

    /**
     * Checks if the serial port flow control has been set.
     *
     * @return bool True if the flow control has been set, false otherwise.
     */
    public function hasFlowControl(): bool
    {
        return $this->flowControl !== null;
    }

    /**
     * Checks if the serial port is opened.
     *
     * @return bool True if the serial port is opened, false otherwise.
     */
    public function isOpened(): bool
    {
        return $this->opened;
    }
}

