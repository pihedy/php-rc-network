<?php declare(strict_types=1);

namespace RcNetwork\Component\Console\Input;

/**
 * InputInterface defines the interface for input objects that provide access 
 * to input arguments and options from the console, command line, or other input sources.
 * 
 * @author Pihe Edmond <pihedy@gmail.com>
 */
interface InputInterface
{
    /**
     * Gets an argument value by name.
     *
     * @param string $name The argument name
     *
     * @return string|null The argument value or null if it doesn't exist
     */
    public function getArgument(string $name);

    /**
     * Gets all argument values.
     *
     * @return array All argument names and values
     */
    public function getArguments(): array;

    /**
     * Gets an option value by name.
     *
     * @param string $name The option name
     *
     * @return string|null The option value or null if it doesn't exist
     */
    public function getOption(string $name);

    /**
     * Gets all option values.
     *
     * @return array All option names and values
     */
    public function getOptions(): array;
}
