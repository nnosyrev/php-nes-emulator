<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use PHPUnit\Framework\TestCase;
use Tests\Integration\CPU\CPUTestTrait;

final class RTSTest extends TestCase
{
    use CPUTestTrait;

    /**
     *   JSR init
     *   JSR loop
     *   JSR end
     *
     * init:
     *   LDX #$00
     *   RTS
     *
     * loop:
     *   INX
     *   CPX #$05
     *   BNE loop
     *   RTS
     *
     * end:
     *   BRK
     */
    public function testRTS(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0x20, 0x09, 0x80, 0x20, 0x0C, 0x80, 0x20, 0x12, 0x80, 0xA2, 0x00, 0x60, 0xE8, 0xE0, 0x05, 0xD0, 0xFB, 0x60, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->getRegisterX()->value, 0x05);
    }
}
