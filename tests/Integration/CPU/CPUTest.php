<?php

declare(strict_types=1);

namespace Tests\Integration\CPU;

use App\Joystick;
use App\Mirroring;
use App\PPU\PPU;
use App\PPU\Register\ControlRegister;
use App\Rom\RomInterface;
use PHPUnit\Framework\TestCase;
use Tests\CPUTestTrait;

final class CPUTest extends TestCase
{
    use CPUTestTrait;

    public function test5opcodes(): void
    {
        $this->loadProgramToRom([0xA9, 0xC0, 0xAA, 0xE8, 0x00]);

        $CPU = $this->getCpu();
        $CPU->run();

        $this->assertSame($CPU->getRegisterX(), 0xC1);
    }

    public function testReadWriteMemoryUInt16(): void
    {
        $this->loadProgramToRom([]);

        /** @var CPU */
        $cpu = $this->getCpu();
        $cpu->setMemoryUInt16(0x0000, 0x8123);

        $this->assertSame($cpu->getMemoryUInt16(0x0000), 0x8123);
    }

    public function testPushPopStack(): void
    {
        $this->loadProgramToRom([]);

        /** @var CPU */
        $cpu = $this->getCpu();
        $cpu->pushToStack(1);
        $cpu->pushToStack(2);
        $cpu->pushToStack(3);

        $this->assertSame($cpu->popFromStack(), 3);
        $this->assertSame($cpu->popFromStack(), 2);
        $this->assertSame($cpu->popFromStack(), 1);
    }

    public function testPushPopStackUInt16(): void
    {
        $this->loadProgramToRom([]);

        /** @var CPU */
        $cpu = $this->getCpu();
        $cpu->pushToStackUInt16(1000);
        $cpu->pushToStackUInt16(2000);
        $cpu->pushToStackUInt16(3000);

        $this->assertSame($cpu->popFromStackUInt16(), 3000);
        $this->assertSame($cpu->popFromStackUInt16(), 2000);
        $this->assertSame($cpu->popFromStackUInt16(), 1000);
    }

    public function testGetMemoryProgram(): void
    {
        $this->loadProgramToRom([1, 2, 3, 4]);

        /** @var CPU */
        $cpu = $this->getCpu();

        $this->assertSame($cpu->getMemory(0x8000 + 0), 1);
        $this->assertSame($cpu->getMemory(0x8000 + 1), 2);
        $this->assertSame($cpu->getMemory(0x8000 + 2), 3);
        $this->assertSame($cpu->getMemory(0x8000 + 3), 4);
    }

    public function testGetSetMemoryRAM(): void
    {
        $this->loadProgramToRom([]);

        /** @var CPU */
        $cpu = $this->getCpu();

        $cpu->setMemory(0x0000, 34);
        $cpu->setMemory(0b111111, 234);
        $cpu->setMemory(0b1111111111, 110);
        // Mirroring
        $cpu->setMemory(0b1111111111111, 93);

        $this->assertSame($cpu->getMemory(0x0000), 34);
        $this->assertSame($cpu->getMemory(0b111111), 234);
        $this->assertSame($cpu->getMemory(0b1111111111), 110);
        // Mirroring
        $this->assertSame($cpu->getMemory(0b1111111111111), 93);
        $this->assertSame($cpu->getMemory(0b11111111111), 93);
    }

    public function testGetSetMemoryJoystick(): void
    {
        $this->loadProgramToRom([]);

        /** @var CPU */
        $cpu = $this->getCpu();
        /** @var Joystick */
        $joystick = $this->container->get(Joystick::class);

        // Only button A
        $cpu->setMemory(0x4016, 1);

        $joystick->setButtonBit(Joystick::BUTTON_A, true);

        $this->assertSame($cpu->getMemory(0x4016), 1);
        $this->assertSame($cpu->getMemory(0x4016), 1);
        $this->assertSame($cpu->getMemory(0x4016), 1);

        $joystick->setButtonBit(Joystick::BUTTON_A, false);

        $this->assertSame($cpu->getMemory(0x4016), 0);
        $this->assertSame($cpu->getMemory(0x4016), 0);
        $this->assertSame($cpu->getMemory(0x4016), 0);

        // Reload
        $cpu->setMemory(0x4016, 1);
        $cpu->setMemory(0x4016, 0);

        $joystick->setButtonBit(Joystick::BUTTON_A, true);
        $joystick->setButtonBit(Joystick::BUTTON_B, true);
        $joystick->setButtonBit(Joystick::BUTTON_SELECT, false);
        $joystick->setButtonBit(Joystick::BUTTON_START, true);
        $joystick->setButtonBit(Joystick::BUTTON_UP, true);
        $joystick->setButtonBit(Joystick::BUTTON_DOWN, false);
        $joystick->setButtonBit(Joystick::BUTTON_LEFT, true);
        $joystick->setButtonBit(Joystick::BUTTON_RIGHT, true);

        $this->assertSame($cpu->getMemory(0x4016), 1);
        $this->assertSame($cpu->getMemory(0x4016), 1);
        $this->assertSame($cpu->getMemory(0x4016), 0);
        $this->assertSame($cpu->getMemory(0x4016), 1);
        $this->assertSame($cpu->getMemory(0x4016), 1);
        $this->assertSame($cpu->getMemory(0x4016), 0);
        $this->assertSame($cpu->getMemory(0x4016), 1);
        $this->assertSame($cpu->getMemory(0x4016), 1);
        $this->assertSame($cpu->getMemory(0x4016), 1);
        $this->assertSame($cpu->getMemory(0x4016), 1);
        $this->assertSame($cpu->getMemory(0x4016), 1);

        // Reload
        $cpu->setMemory(0x4016, 1);
        $cpu->setMemory(0x4016, 0);

        $joystick->setButtonBit(Joystick::BUTTON_A, false);
        $joystick->setButtonBit(Joystick::BUTTON_B, false);
        $joystick->setButtonBit(Joystick::BUTTON_SELECT, true);
        $joystick->setButtonBit(Joystick::BUTTON_START, false);
        $joystick->setButtonBit(Joystick::BUTTON_UP, false);
        $joystick->setButtonBit(Joystick::BUTTON_DOWN, true);
        $joystick->setButtonBit(Joystick::BUTTON_LEFT, false);
        $joystick->setButtonBit(Joystick::BUTTON_RIGHT, false);

        $this->assertSame($cpu->getMemory(0x4016), 0);
        $this->assertSame($cpu->getMemory(0x4016), 0);
        $this->assertSame($cpu->getMemory(0x4016), 1);
        $this->assertSame($cpu->getMemory(0x4016), 0);
        $this->assertSame($cpu->getMemory(0x4016), 0);
        $this->assertSame($cpu->getMemory(0x4016), 1);
        $this->assertSame($cpu->getMemory(0x4016), 0);
        $this->assertSame($cpu->getMemory(0x4016), 0);
        $this->assertSame($cpu->getMemory(0x4016), 1);
        $this->assertSame($cpu->getMemory(0x4016), 1);
        $this->assertSame($cpu->getMemory(0x4016), 1);
    }

    public function testGetSetMemoryOamData(): void
    {
        $this->loadProgramToRom([]);

        /** @var CPU */
        $cpu = $this->getCpu();

        // Setting OAMADDR
        $cpu->setMemory(0x2003, 34);
        // Setting OAMDATA
        $cpu->setMemory(0x2004, 100);
        $cpu->setMemory(0x2004, 167);
        $cpu->setMemory(0x2004, 0);
        $cpu->setMemory(0x2004, 255);

        $cpu->setMemory(0x2003, 34);
        $this->assertSame($cpu->getMemory(0x2004), 100);

        $cpu->setMemory(0x2003, 35);
        $this->assertSame($cpu->getMemory(0x2004), 167);

        $cpu->setMemory(0x2003, 36);
        $this->assertSame($cpu->getMemory(0x2004), 0);

        $cpu->setMemory(0x2003, 37);
        $this->assertSame($cpu->getMemory(0x2004), 255);
    }

    public function testGetSetMemoryOamDma(): void
    {
        $this->loadProgramToRom([]);

        /** @var CPU */
        $cpu = $this->getCpu();
        /** @var PPU */
        $ppu = $this->container->get(PPU::class);

        $N = 5;

        // Setting OAMADDR
        $cpu->setMemory(0x2003, 0);

        for ($i = 0; $i <= 255; $i++) {
            $cpu->setMemory(0x100 * $N + $i, $i);
        }

        // Setting OAMDMA
        $cpu->setMemory(0x4014, $N);

        $allOamData = $ppu->getAllOamData();

        for ($i = 0; $i <= 255; $i++) {
            $this->assertSame($allOamData[$i], $i);
        }
    }

    public function testGetSetMemoryData(): void
    {
        $rom = $this->createStub(RomInterface::class);
        $rom->method('getMirroring')
            ->willReturn(Mirroring::Vertical);

        $this->container->set(RomInterface::class, $rom);

        /** @var CPU */
        $cpu = $this->getCpu();

        // Setting PPUCTRL (increment is 1)
        $cpu->setMemory(0x2000, 0b00000000);

        // Setting PPUADDR to 0x2108
        $cpu->setMemory(0x2006, 0x21);
        $cpu->setMemory(0x2006, 0x08);

        // Setting PPUDATA
        $cpu->setMemory(0x2007, 57);
        $cpu->setMemory(0x2007, 63);
        $cpu->setMemory(0x2007, 243);
        $cpu->setMemory(0x2007, 0);
        $cpu->setMemory(0x2007, 255);
        $cpu->setMemory(0x2007, 5);

        // Setting PPUADDR to 0x2108
        $cpu->setMemory(0x2006, 0x21);
        $cpu->setMemory(0x2006, 0x08);

        // Ignore first read buffer
        $cpu->getMemory(0x2007);

        $this->assertSame($cpu->getMemory(0x2007), 57);
        $this->assertSame($cpu->getMemory(0x2007), 63);
        $this->assertSame($cpu->getMemory(0x2007), 243);
        $this->assertSame($cpu->getMemory(0x2007), 0);
        $this->assertSame($cpu->getMemory(0x2007), 255);
        $this->assertSame($cpu->getMemory(0x2007), 5);
    }

    public function testGetSetMemoryControl(): void
    {
        $this->loadProgramToRom([]);

        /** @var CPU */
        $cpu = $this->getCpu();
        /** @var ControlRegister */
        $controlRegister = $this->container->get(ControlRegister::class);

        // Setting PPUCTRL
        $cpu->setMemory(0x2000, 0b00000000);

        $this->assertSame($controlRegister->getAddressIncrement(), 1);
        $this->assertSame($controlRegister->getNMIEnableBit(), false);
        $this->assertSame($controlRegister->getSpritePatternTableBit(), false);
        $this->assertSame($controlRegister->getBackgroundPatternTableBit(), false);

        // Setting PPUCTRL
        $cpu->setMemory(0x2000, 0b10011100);

        $this->assertSame($controlRegister->getAddressIncrement(), 32);
        $this->assertSame($controlRegister->getNMIEnableBit(), true);
        $this->assertSame($controlRegister->getSpritePatternTableBit(), true);
        $this->assertSame($controlRegister->getBackgroundPatternTableBit(), true);
    }
}
