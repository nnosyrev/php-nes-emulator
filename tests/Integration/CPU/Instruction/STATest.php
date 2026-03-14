<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use App\Type\UInt16;
use App\Type\UInt8;
use PHPUnit\Framework\TestCase;
use Tests\CPUTestTrait;

final class STATest extends TestCase
{
    use CPUTestTrait;

    public function testSTAZeroPage(): void
    {
        $this->loadProgramToRom([0xA9, 0x05, 0x85, 0x34, 0x00]);

        $CPU = $this->getCpu();
        $CPU->run();

        $this->assertSame($CPU->getMemory(new UInt16(0x34))->value, 0x05);
    }

    public function testSTAZeroPageX(): void
    {
        $this->loadProgramToRom([0xA9, 0x05, 0xA2, 0x01, 0x95, 0x34, 0x00]);

        $CPU = $this->getCpu();
        $CPU->run();

        $this->assertSame($CPU->getMemory(new UInt16(0x35))->value, 0x05);
    }

    public function testSTAAbsolute(): void
    {
        $this->loadProgramToRom([0xA9, 0x05, 0x8D, 0x34, 0x01, 0x00]);

        $CPU = $this->getCpu();
        $CPU->run();

        $this->assertSame($CPU->getMemory(new UInt16(0x0134))->value, 0x05);
    }

    public function testSTAAbsoluteX(): void
    {
        $this->loadProgramToRom([0xA9, 0x05, 0xA2, 0x01, 0x9D, 0x34, 0x01, 0x00]);

        $CPU = $this->getCpu();
        $CPU->run();

        $this->assertSame($CPU->getMemory(new UInt16(0x0135))->value, 0x05);
    }

    public function testSTAAbsoluteY(): void
    {
        $this->loadProgramToRom([0xA9, 0x05, 0xA0, 0x01, 0x99, 0x34, 0x01, 0x00]);

        $CPU = $this->getCpu();
        $CPU->run();

        $this->assertSame($CPU->getMemory(new UInt16(0x0135))->value, 0x05);
    }

    public function testSTAIndirectX(): void
    {
        $this->loadProgramToRom([0xA9, 0x05, 0xA2, 0x01, 0x81, 0x34, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setMemory(new UInt16(0x35), new UInt8(0x02));
        $CPU->setMemory(new UInt16(0x36), new UInt8(0x01));
        $CPU->run();

        $this->assertSame($CPU->getMemory(new UInt16(0x0102))->value, 0x05);
    }

    public function testSTAIndirectY(): void
    {
        $this->loadProgramToRom([0xA9, 0x05, 0xA0, 0x01, 0x91, 0x34, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setMemory(new UInt16(0x34), new UInt8(0x02));
        $CPU->setMemory(new UInt16(0x35), new UInt8(0x01));
        $CPU->run();

        $this->assertSame($CPU->getMemory(new UInt16(0x0103))->value, 0x05);
    }
}
