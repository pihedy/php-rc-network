<?php declare(strict_types=1);

namespace RcNetwork\Components\Serial;

/**
 * Represents a serial communication interface.
 * 
 * This class provides methods for executing commands on a serial device and retrieving the output.
 * 
 * @author Pihe Edmond <pihedy@gmail.com>
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
     * Constructs a new instance of the `Serial` class, injecting a `DeviceInterface` implementation.
     *
     * @param DeviceInterface $Device The device interface implementation to use.
     */
    public function __construct(protected DeviceInterface $Device)
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

        $answers = [];

        foreach ($pipes as $pipeIndex => $pipe) {
            $answers[$pipeIndex] = stream_get_contents($pipe);

            fclose($pipe);
        }

        /**
         * Closes the process opened by `proc_open()` and returns the exit status of the process.
         * 
         * TODO: 
         * Meg kell majd nézni, hogy direkt ezzel vissza tudok-e térni, 
         * és előtte nyugodtan kitehetem a választ az instance-be.
         */
        $status = proc_close($process);

        /**
         * Stores the output of the executed command in the `$answers` property.
         */
        $this->answers = $answers;

        return $status;
    }

    public function open(): self
    {
        if ($this->Device->isOpened()) {
            throw new \RuntimeException('Device is already opened.');
        }

        $status = $this->exec("stty -F {$this->Device->getPort()} 2>&1");

        if ($status !== 0) {
            throw new \RuntimeException("Could not open device: {$this->getAnswerByIndex(self::STD_ERROR)}");
        }

        $this->Device->setOpened(true);

        return $this;
    }

    public function write(): void
    {
        
    }

    public function read(): void
    {
        
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