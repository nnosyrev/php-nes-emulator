<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use PHPUnit\Framework\TestCase;
use Tests\Integration\CPU\CPUTestTrait;

final class BNETest extends TestCase
{
    use CPUTestTrait;

    public function testBNEFlagZIsFalse(): void
    {
        $this->loadProgramToRom([0xE8, 0x00, 0xA2, 0x05, 0xD0, 0xFA, 0x00]);

        $CPU = $this->getCpu();
        $CPU->incrementPC();
        $CPU->incrementPC();
        $CPU->run();

        $this->assertSame($CPU->getRegisterX()->value, 0x06);
    }

    public function testBNEFlagZIsTrue(): void
    {
        $this->loadProgramToRom([0xE8, 0x00, 0xA2, 0x00, 0xD0, 0xFA, 0x00]);

        $CPU = $this->getCpu();
        $CPU->incrementPC();
        $CPU->incrementPC();
        $CPU->run();

        $this->assertSame($CPU->getRegisterX()->value, 0x00);
    }
}
