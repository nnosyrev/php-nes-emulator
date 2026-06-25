<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use PHPUnit\Framework\TestCase;
use Tests\CPUTestTrait;

final class LAXTest extends TestCase
{
    use CPUTestTrait;

    public function testLAXZeroPage(): void
    {
        $this->loadProgramToRom([0xA7, 0x05, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setMemory(0x05, 0x11);
        $CPU->run();

        $this->assertSame($CPU->getRegisterA(), 0x11);
        $this->assertSame($CPU->getRegisterX(), 0x11);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
    }
}
