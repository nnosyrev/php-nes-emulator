<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use App\Type\UInt16;
use App\Type\UInt8;
use PHPUnit\Framework\TestCase;
use Tests\CPUTestTrait;

final class AXATest extends TestCase
{
    use CPUTestTrait;

    public function testAXAAbsoluteY(): void
    {
        $this->loadProgramToRom([0xA9, 0x05, 0xA2, 0x11, 0xA0, 0x03, 0x9F, 0x10, 0x02, 0x00]);

        $CPU = $this->getCpu();
        $CPU->run();

        $this->assertSame($CPU->getMemory(new UInt16(0x0213))->value, 0x05 & 0x11 & 0x02);
    }

    public function testAXAIndirectY(): void
    {
        $this->loadProgramToRom([0xA9, 0x05, 0xA2, 0x11, 0xA0, 0x01, 0x93, 0x01, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setMemory(new UInt16(0x01), new UInt8(0x03));
        $CPU->setMemory(new UInt16(0x02), new UInt8(0x07));
        $CPU->run();

        $this->assertSame($CPU->getMemory(new UInt16(0x0704))->value, 0x05 & 0x11 & 0x07);
    }
}
