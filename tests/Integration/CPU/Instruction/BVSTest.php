<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use PHPUnit\Framework\TestCase;
use Tests\Integration\CPU\CPUTestTrait;

final class BVSTest extends TestCase
{
    use CPUTestTrait;

    public function testBVS(): void
    {
        $CPU = $this->CPU;
        $CPU->setFlagV(true);
        $CPU->load([0xE8, 0x00, 0xA2, 0x05, 0x70, 0xFA, 0x00]);
        $CPU->incrementPC();
        $CPU->incrementPC();
        $CPU->run();

        $this->assertSame($CPU->getRegisterX()->value, 0x06);
    }

    public function testBVSFlagVIsFalse(): void
    {
        $CPU = $this->CPU;
        $CPU->setFlagV(false);
        $CPU->load([0xE8, 0x00, 0xA2, 0x05, 0x70, 0xFA, 0x00]);
        $CPU->incrementPC();
        $CPU->incrementPC();
        $CPU->run();

        $this->assertSame($CPU->getRegisterX()->value, 0x05);
    }
}
