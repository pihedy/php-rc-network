<?php declare(strict_types=1);

namespace RcNetwork\Components\Serial;

/**
 * Defines an interface for interacting with a serial port.
 *
 * @author Pihe Edmond <pihedy@gmail.com>
 */
interface SerialInterface
{
    /**
     * Executes the given command and returns the exit code.
     *
     * @param string $command The command to execute.
     *
     * @return int The exit code of the executed command.
     */
    public function exec(string $command): int;

    /**
     * Opens the serial port and returns the current instance for method chaining.
     *
     * @return $this The current instance of the SerialInterface implementation.
     */
    public function open(): self;

    /**
     * Writes the given data to the serial port.
     *
     * @param string $data The data to write to the serial port.
     */
    public function write(string $data): void;

    /**
     * Reads data from the serial port and returns it as a string.
     *
     * @return string The data read from the serial port.
     */
    public function read(): string;

    /**
     * Closes the serial port.
     */
    public function close(): void;
}
