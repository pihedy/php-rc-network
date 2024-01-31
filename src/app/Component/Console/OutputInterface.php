<?php declare(strict_types=1);

namespace RcNetwork\Component\Console;

/**
 * OutputInterface defines an interface for outputting text to the console.
 * 
 * @author Pihe Edmond <pihedy@gmail.com>
 */
interface OutputInterface
{
    /**
     * Writes a message to the output.
     */
    public function write(string $message);

    /**
     * Writes a message to the output and appends a newline.
     */
    public function writeln(string $message);

    /**
     * Formats the arguments and writes them to the output.
     */
    public function writef(string $format, ...$args);
}
