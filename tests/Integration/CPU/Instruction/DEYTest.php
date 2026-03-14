<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use PHPUnit\Framework\TestCase;
use Tests\CPUTestTrait;

final class DEYTest extends TestCase
{
    use CPUTestTrait;

    public function testDEY(): void
    {
        $this->loadProgramToRom([0xA0, 0x05, 0x88, 0x00]);

        $CPU = $this->getCpu();
        $CPU->run();

        $this->assertSame($CPU->getRegisterY()->value, 0x05 - 1);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterY()));
    }

    public function testDEYOverflow(): void
    {
        $this->loadProgramToRom([0xA0, 0x01, 0x88, 0x88, 0x00]);

        $CPU = $this->getCpu();
        $CPU->run();

        $this->assertSame($CPU->getRegisterY()->value, 0xFF);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterY()));
    }
}
