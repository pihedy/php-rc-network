<?php declare(strict_types=1);

namespace Tests\Unit\Components\Serial;

use \PHPUnit\Framework\TestCase;

use \RcNetwork\Components\Serial\Serial;

final class SerialTest extends TestCase
{
    private Serial $Serial;

    public function setUp(): void
    {
        error_reporting(E_ALL);

        $this->Serial = new Serial([
            'port' => '/dev/pts/2',
        ]);
    }

    public function testValidateOperationSystem(): void
    {
        $this->assertIsBool($this->Serial->validateOperationSystem());
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

    public function testDeviceHasPort(): void
    {
        $this->assertTrue($this->Serial->Device->hasPort());
    }

    public function testDeviceCheckPort(): void
    {
        $this->assertEquals($this->Serial->Device->getPort(), '/dev/pts/2');
    }
}
