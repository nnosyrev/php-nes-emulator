<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use App\Type\UInt16;
use App\Type\UInt8;
use PHPUnit\Framework\TestCase;
use Tests\CPUTestTrait;

final class STXTest extends TestCase
{
    use CPUTestTrait;

    public function testSTXZeroPage(): void
    {
        $this->loadProgramToRom([0xA2, 0x05, 0x86, 0x34, 0x00]);

        $CPU = $this->getCpu();
        $CPU->run();

        $this->assertSame($CPU->getMemory(0x34), 0x05);
    }

    public function testSTXZeroPageY(): void
    {
        $this->loadProgramToRom([0xA2, 0x05, 0x96, 0x34, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setRegisterY(0x01);
        $CPU->run();

        $this->assertSame($CPU->getMemory(0x35), 0x05);
    }

    public function testSTXAbsolute(): void
    {
        $this->loadProgramToRom([0xA2, 0x05, 0x8E, 0x34, 0x01, 0x00]);

        $CPU = $this->getCpu();
        $CPU->run();

        $this->assertSame($CPU->getMemory(0x0134), 0x05);
    }
}
