<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use PHPUnit\Framework\TestCase;
use Tests\CPUTestTrait;

final class BVCTest extends TestCase
{
    use CPUTestTrait;

    public function testBVC(): void
    {
        $this->loadProgramToRom([0xE8, 0x00, 0xA2, 0x05, 0x50, 0xFA, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setFlagV(true);
        $CPU->incrementPC();
        $CPU->incrementPC();
        $CPU->run();

        $this->assertSame($CPU->getRegisterX()->value, 0x05);
    }

    public function testBVCFlagVIsFalse(): void
    {
        $this->loadProgramToRom([0xE8, 0x00, 0xA2, 0x05, 0x50, 0xFA, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setFlagV(false);
        $CPU->incrementPC();
        $CPU->incrementPC();
        $CPU->run();

        $this->assertSame($CPU->getRegisterX()->value, 0x06);
    }
}
