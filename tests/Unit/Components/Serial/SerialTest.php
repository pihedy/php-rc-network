<?php declare(strict_types=1);

namespace Tests\Unit\Components\Serial;

use \PHPUnit\Framework\TestCase;
use \RcNetwork\Components\Serial\ {Serial, Device};

final class SerialTest extends TestCase
{
    private Serial $Serial;

    public function setUp(): void
    {
        error_reporting(E_ALL);

        $this->Serial = new Serial(new Device([
            'port' => '/dev/pts/2',
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
