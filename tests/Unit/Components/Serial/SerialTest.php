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
            'port'              => '/dev/pts/2',
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

    public function testOpenSuccessfully(): void
    {
        $result = $this->Serial->open();

        $this->assertInstanceOf(Serial::class, $result);
        $this->assertTrue($this->Serial->getDevice()->isOpened());
    }

    public function testWriteSuccessfully(): void
    {
        $this->Serial->open()->write("Unit Test data\r\n");

        $this->expectNotToPerformAssertions();
    }

    public function testReadReturnsString(): void
    {
        $result = $this->Serial->open()->read();

        $this->assertIsString($result);
    }

    public function testReadWithCustomLength(): void
    {
        $length = 64;
        $result = $this->Serial->open()->read($length);

        $this->assertLessThanOrEqual($length, strlen($result));
    }

    public function testReadWithCustomTimeout(): void
    {
        $timeout    = 0.5;
        $start      = microtime(true);

        $this->Serial->open()->read(128, $timeout);

        $end = microtime(true);

        $this->assertLessThanOrEqual($timeout + 0.1, $end - $start);
    }

    public function testReadReturnsEmptyStringOnTimeout(): void
    {
        $result = $this->Serial->open()->read(128, 0.001);

        $this->assertSame('', $result);
    }
}
