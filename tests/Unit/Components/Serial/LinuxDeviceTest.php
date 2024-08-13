<?php declare(strict_types=1);

namespace Tests\Unit\Components\Serial;

use \PHPUnit\Framework\TestCase;

use \RcNetwork\Components\Serial\LinuxDevice;

/**
 * Represents a test suite for the `LinuxDevice` class, which provides an interface for interacting with a Linux serial device.
 *
 * This test suite ensures that the `LinuxDevice` class behaves as expected, including:
 * - Setting and retrieving the serial port, baud rate, parity, character size, stop bits, and flow control.
 * - Handling invalid input for these settings.
 * - Ensuring the device is not opened by default.
 * - Throwing an exception when attempting to set the port while the device is already opened.
 *
 * @author Pihe Edmond <pihedy@gmail.com>
 */
class LinuxDeviceTest extends TestCase
{
    /**
     * Represents a Linux serial device.
     */
    private LinuxDevice $Device;

    /**
     * Sets up the LinuxDevice instance with the specified configuration.
     */
    protected function setUp(): void
    {
        $this->Device = new LinuxDevice([
            'port'              => '/dev/ttyS0',
            'boudrate'          => 9600,
            'parity'            => 'none',
            'character_size'    => 8,
            'stop_bits'         => false,
            'flow_control'      => 'none',
        ]);
    }

    /**
     * Tests setting the serial port to a Windows-style COM port.
     *
     * This test ensures that when a Windows-style COM port is set,
     * the corresponding Linux device path is returned.
     */
    public function testSetPortWithCOMPort(): void
    {
        $this->Device->setPort('COM3');

        $this->assertEquals('/dev/ttyS2', $this->Device->getPort());
    }

    /**
     * Tests setting the serial port to a Linux device path.
     *
     * This test ensures that when a Linux device path is set,
     * the same path is returned.
     */
    public function testSetPortWithLinuxPort(): void
    {
        $this->Device->setPort('/dev/ttyUSB0');

        $this->assertEquals('/dev/ttyUSB0', $this->Device->getPort());
    }

    /**
     * Tests setting the boudrate to a valid value.
     *
     * This test ensures that when a valid boudrate is set,
     * the same value is returned.
     */
    public function testSetBoudrateWithValidRate(): void
    {
        $this->Device->setBaudrate(9600);

        $this->assertEquals(9600, $this->Device->getBaudrate());
    }

    /**
     * Tests setting the boudrate to an invalid value.
     *
     * This test ensures that when an invalid boudrate is set,
     * an exception is thrown.
     */
    public function testSetBaudrateWithInvalidRate(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid baudrate.');

        $this->Device->setBaudrate(1234);
    }

    /**
     * Tests setting the parity to valid values.
     *
     * This test ensures that when a valid parity value is set,
     * the corresponding parity flags are returned.
     */
    public function testSetParityWithValidValues(): void
    {
        $this->Device->setParity('none');
        $this->assertEquals('-parenb', $this->Device->getParity());

        $this->Device->setParity('even');
        $this->assertEquals('parenb -parodd', $this->Device->getParity());

        $this->Device->setParity('odd');
        $this->assertEquals('parenb parodd', $this->Device->getParity());
    }

    /**
     * Tests setting the parity to an invalid value.
     *
     * This test ensures that when an invalid parity value is set,
     * an exception is thrown with the appropriate error message.
     */
    public function testSetParityWithInvalidValue(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid parity.');

        $this->Device->setParity('invalid');
    }

    /**
     * Tests setting the character size to a valid value.
     *
     * This test ensures that when a valid character size is set,
     * the corresponding character size flag is returned.
     */
    public function testSetCharacterSizeWithValidValues(): void
    {
        $this->Device->setCharacterSize(5);
        $this->assertEquals("cs5", $this->Device->getCharacterSize());
    }

    /**
     * Tests setting the character size to invalid values.
     *
     * This test ensures that when an invalid character size is set,
     * an exception is thrown with the appropriate error message.
     */
    public function testSetCharacterSizeWithInvalidValues(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid character size.');

        $this->Device->setCharacterSize(4);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid character size.');

        $this->Device->setCharacterSize(9);
    }

    /**
     * Tests setting the stop bits to valid values.
     *
     * This test ensures that when a valid stop bits value is set,
     * the corresponding stop bits flag is returned.
     */
    public function testSetStopBits(): void
    {
        $this->Device->setStopBits(true);
        $this->assertEquals('-cstopb', $this->Device->getStopBits());

        $this->Device->setStopBits(false);
        $this->assertEquals('cstopb', $this->Device->getStopBits());
    }

    /**
     * Tests setting the flow control to valid values.
     *
     * This test ensures that when a valid flow control value is set,
     * the corresponding flow control flag is returned.
     */
    public function testSetFlowControlWithValidValues(): void
    {
        $this->Device->setFlowControl('none');
        $this->assertEquals('', $this->Device->getFlowControl());

        $this->Device->setFlowControl('rts');
        $this->assertEquals('-crtscts', $this->Device->getFlowControl());

        $this->Device->setFlowControl('cts');
        $this->assertEquals('-ccts', $this->Device->getFlowControl());

        $this->Device->setFlowControl('rtscts');
        $this->assertEquals('-crtscts', $this->Device->getFlowControl());
    }

    /**
     * Tests setting the flow control to an invalid value.
     *
     * This test ensures that when an invalid flow control value is set,
     * an exception is thrown with the appropriate error message.
     */
    public function testSetFlowControlWithInvalidValue(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid flow control.');
        $this->Device->setFlowControl('invalid');
    }

    /**
     * Tests that the device has the expected methods and that they work as expected.
     *
     * This test ensures that the device has the following methods and that they function correctly:
     *
     * - `hasPort()`
     * - `setPort()`
     * - `hasBoudrate()`
     * - `setBoudrate()`
     * - `hasParity()`
     * - `setParity()`
     * - `hasCharacterSize()`
     * - `setCharacterSize()`
     * - `hasStopBits()`
     * - `setStopBits()`
     * - `hasFlowControl()`
     * - `setFlowControl()`
     */
    public function testHasMethods(): void
    {
        $this->assertFalse($this->Device->hasPort());
        $this->Device->setPort('/dev/ttyUSB0');
        $this->assertTrue($this->Device->hasPort());

        $this->assertFalse($this->Device->hasBaudrate());
        $this->Device->setBaudrate(9600);
        $this->assertTrue($this->Device->hasBaudrate());

        $this->assertFalse($this->Device->hasParity());
        $this->Device->setParity('none');
        $this->assertTrue($this->Device->hasParity());

        $this->assertFalse($this->Device->hasCharacterSize());
        $this->Device->setCharacterSize(8);
        $this->assertTrue($this->Device->hasCharacterSize());

        $this->assertFalse($this->Device->hasStopBits());
        $this->Device->setStopBits(true);
        $this->assertTrue($this->Device->hasStopBits());

        $this->assertFalse($this->Device->hasFlowControl());
        $this->Device->setFlowControl('none');
        $this->assertTrue($this->Device->hasFlowControl());
    }

    /**
     * Tests that the device is not opened by default.
     */
    public function testIsOpened(): void
    {
        $this->assertFalse($this->Device->isOpened());
    }

    /**
     * Tests that setting the port throws an exception if the device is already opened.
     */
    public function testSetOpenedThrowsExceptionWhenAlreadyOpened(): void
    {
        $this->Device->setOpened(true);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Device already opened.');

        $this->Device->setPort('/dev/ttyS0');
    }

    /**
     * Tests that the device is configured with default settings.
     */
    public function testBuildCommandWithDefaultSettings(): void
    {
        $expectedCommand = 'stty -F /dev/ttyS0 9600 cs8 cstopb -parenb';
        $this->assertEquals($expectedCommand, $this->Device->getSetupCommand());
    }

    /**
     * Tests that the device is configured with custom settings.
     */
    public function testBuildCommandWithCustomSettings(): void
    {
        $this->Device->setPort('/dev/ttyUSB0');
        $this->Device->setBaudrate(115200);
        $this->Device->setParity('even');
        $this->Device->setCharacterSize(7);
        $this->Device->setStopBits(true);
        $this->Device->setFlowControl('rtscts');

        $expectedCommand = 'stty -F /dev/ttyUSB0 115200 cs7 -cstopb parenb -parodd -crtscts';
        $this->assertEquals($expectedCommand, $this->Device->getSetupCommand());
    }

    /**
     * Tests that the device is configured with minimal settings.
     */
    public function testBuildCommandWithMinimalSettings(): void
    {
        $minimalDevice = new LinuxDevice([
            'port' => '/dev/ttyACM0',
        ]);

        $expectedCommand = 'stty -F /dev/ttyACM0 9600 cs8 cstopb -parenb';
        $this->assertEquals($expectedCommand, $minimalDevice->getSetupCommand());
    }

    /**
     * Tests that the device is configured with odd parity.
     */
    public function testBuildCommandWithOddParity(): void
    {
        $this->Device->setParity('odd');
        $expectedCommand = 'stty -F /dev/ttyS0 9600 cs8 cstopb parenb parodd';
        $this->assertEquals($expectedCommand, $this->Device->getSetupCommand());
    }
}
