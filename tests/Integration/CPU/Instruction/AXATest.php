<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

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

        $this->assertSame($CPU->getMemory(0x0213), 0x05 & 0x11 & 0x02);
    }

    public function testAXAIndirectY(): void
    {
        $this->loadProgramToRom([0xA9, 0x05, 0xA2, 0x11, 0xA0, 0x01, 0x93, 0x01, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setMemory(0x01, 0x03);
        $CPU->setMemory(0x02, 0x07);
        $CPU->run();

        $this->assertSame($CPU->getMemory(0x0704), 0x05 & 0x11 & 0x07);
    }
}
