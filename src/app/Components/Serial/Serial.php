<?php declare(strict_types=1);

namespace RcNetwork\Components\Serial;

/**
 * Represents a serial communication interface.
 *
 * This class provides methods for executing commands on a serial device and retrieving the output.
 *
 * @author Pihe Edmond <pihedy@gmail.com>
 *
 * @property DeviceInterface $Device The command to be executed on the serial device.
 */
class Serial implements SerialInterface
{
    /**
     * Constant representing the standard input file descriptor.
     */
    public const STD_INPUT = 0;

    /**
     * Constant representing the standard output file descriptor.
     */
    public const STD_OUTPUT = 1;

    /**
     * Constant representing the standard error file descriptor.
     */
    public const STD_ERROR = 2;

    /**
     * An array of file descriptor specifications for the standard input, output, and error streams.
     *
     * @var array
     */
    protected static array $descriptors = [
        self::STD_OUTPUT    => ['pipe', 'w'],
        self::STD_ERROR     => ['pipe', 'w'],
    ];

    /**
     * An array to store answers.
     *
     * @var array
     */
    protected array $answers = [];

    /**
     * Stores the stream resource for the serial communication.
     */
    protected mixed $stream = null;

    /**
     * Constructs a new instance of the `Serial` class, injecting a `DeviceInterface` implementation.
     *
     * @param DeviceInterface $Device The device interface implementation to use.
     */
    public function __construct(public readonly DeviceInterface $Device)
    {
        $this->validateOperationSystem();
    }

    /**
     * Returns the answers collected from the executed command.
     *
     * @return array The answers collected from the executed command.
     */
    public function getAnswers(): array
    {
        return $this->answers;
    }

    /**
     * Returns the answer at the specified index from the collected answers.
     *
     * @param int $index The index of the answer to retrieve.
     *
     * @return string|null The answer at the specified index, or null if the index is out of bounds.
     */
    public function getAnswerByIndex(int $index): ?string
    {
        if (!isset(self::$descriptors)) {
            throw  new \RuntimeException('Descriptors not initialized.');
        }

        return $this->answers[$index] ?? null;
    }

    /**
     * Executes the given command and returns the exit status.
     *
     * This method opens a new process using the `proc_open()` function, executes the provided command,
     * and returns the exit status of the process.
     * The output of the command is stored in the `$answers` property of the class.
     *
     * @param string $command The command to execute.
     *
     * @return int The exit status of the executed command.
     *
     * @throws \RuntimeException If the command could not be executed.
     */
    public function exec(string $command): int
    {
        $process = proc_open($command, self::$descriptors, $pipes);

        if (!is_resource($process)) {
            throw new \RuntimeException("Could not execute command: {$command}");
        }

        try {
            $answers = [];

            foreach ($pipes as $pipeIndex => $pipe) {
                $answers[$pipeIndex] = stream_get_contents($pipe);

                fclose($pipe);
            }

            /**
             * Stores the output of the executed command in the `$answers` property.
             */
            $this->answers = $answers;
        } finally {
            /**
             * Closes the process opened by `proc_open()` and returns the exit status of the process.
             */
            $status = proc_close($process);
        }

        return $status;
    }

    /**
     * Opens the serial device and sets up the connection.
     *
     * This method executes the setup command for the serial device,
     * opens the device port, and sets the stream to non-blocking mode.
     * If the device is already opened, it throws a `RuntimeException`.
     *
     * @return $this The current instance of the `Serial` class.
     *
     * @throws \RuntimeException If the device is already opened, the setup command fails, or the device port cannot be opened.
     */
    public function open(): self
    {
        if ($this->Device->isOpened()) {
            throw new \RuntimeException('Device is already opened.');
        }

        $status = $this->exec($this->Device->getSetupCommand());

        if ($status !== 0) {
            throw new \RuntimeException("Could not open device: {$this->getAnswerByIndex(self::STD_ERROR)}");
        }

        $resource = fopen($this->Device->getPort(), 'r+b');

        if ($resource === false) {
            throw new \RuntimeException("Could not open device: {$this->getAnswerByIndex(self::STD_ERROR)}");
        }

        stream_set_blocking($resource, false);

        $this->stream = $resource;

        $this->Device->setOpened(true);

        return $this;
    }

    /**
     * Writes data to the serial device.
     *
     * @param string    $data   The data to write to the serial device.
     * @param float     $wait   The time in seconds to wait after writing the data.
     *
     * @throws \RuntimeException If the device is not opened or an error occurs while writing.
     */
    public function write(string $data, float $wait = 0.1): void
    {
        if (!$this->Device->isOpened()) {
            throw new \RuntimeException('Device is not opened.');
        }

        $status = fwrite($this->stream, $data);

        if ($status === false) {
            throw new \RuntimeException("Could not write to device: {$this->getAnswerByIndex(self::STD_ERROR)}");
        }

        usleep((int) ($wait * 1000000));
    }

    /**
     * Reads data from the serial device.
     *
     * @param int       $length     The maximum number of bytes to read.
     * @param float     $timeout    The maximum time in seconds to wait for data to become available.
     *
     * @return string The data read from the serial device.
     *
     * @throws \RuntimeException If the device is not opened or an error occurs while reading.
     */
    public function read(int $length = 128, float $timeout = 1.0): string
    {
        if (!$this->Device->isOpened()) {
            throw new \RuntimeException('Device is not opened.');
        }

        $result = '';
        $read   = [$this->stream];
        $write  = null;
        $except = null;

        $timeoutSecunds = (int) floor($timeout);
        $timeoutMicros  = (int) (($timeout - $timeoutSecunds) * 1000000);

        while (strlen($result) < $length) {
            $ready = stream_select($read, $write, $except, $timeoutSecunds, $timeoutMicros);

            if ($ready === false) {
                throw new \RuntimeException('Error occurred while waiting for stream to be ready.');
            }

            if ($ready === 0) {
                break;
            }

            $data = fread($this->stream, $length - strlen($result));

            if ($data === false) {
                throw new \RuntimeException('Error reading from device.');
            }

            $result .= $data;

            /**
             * Reset the read stream array.
             */
            $read = [$this->stream];
        }

        return $result;
    }

    /**
     * Closes the serial device connection.
     *
     * This method sets the `$opened` property of the `$Device` object to `false`,
     * indicating that the serial device is now closed.
     *
     * @throws \RuntimeException If the device is already closed.
     */
    public function close(): void
    {
        if (!$this->Device->isOpened()) {
            throw new \RuntimeException('Device is already closed.');
        }

        if (is_resource(($this->stream))) {
            fclose($this->stream);
        }

        $this->Device->setOpened(false);
    }

    /**
     * Validates that the current operating system is Linux.
     *
     * @return bool `true` if the operating system is Linux and `stty` is available, `false` otherwise.
     */
    protected function validateOperationSystem(): bool
    {
        $system = php_uname();

        if (!preg_match('/linux/', strtolower($system), $output)) {
            throw new \RuntimeException('This library is only compatible with linux.');
        }

        $status = $this->exec('stty --version');

        if ($status !== 0) {
            throw new \RuntimeException('No stty available, unable to run.');
        }

        return true;
    }
}
