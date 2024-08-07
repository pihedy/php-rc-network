<?php declare(strict_types=1);

namespace Tests\Unit\Components\Serial;

use \PHPUnit\Framework\TestCase;
use \RcNetwork\Components\Serial\ {Serial, LinuxDevice};

/**
 * Tests the functionality of the `Serial` class,
 * which provides an interface for interacting with serial devices.
 *
 * This test suite verifies that the `exec()` method returns an integer value,
 * and that the `getAnswerByIndex()` method returns a string value.
 *
 * @author Pihe Edmond <pihedy@gmail.com>
 */
final class SerialTest extends TestCase
{
    private Serial $Serial;

    public function setUp(): void
    {
        $this->Serial = new Serial(new LinuxDevice([
            'port'              => '/dev/ttyS0',
            'boudrate'          => 9600,
            'parity'            => 'none',
            'character_size'    => 8,
            'stop_bits'         => false,
            'flow_control'      => 'none',
        ]));
    }

    public function testExecReturnIsInt(): void
    {
        $this->assertIsInt($this->Serial->exec('ls -l'));
    }

    public function testGetAnswerByIndexIsString(): void
    {
        $this->Serial->exec('ls -l');

        $this->assertIsString($this->Serial->getAnswerByIndex(Serial::STD_OUTPUT));
    }
}
