<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use PHPUnit\Framework\TestCase;
use Tests\CPUTestTrait;

final class LARTest extends TestCase
{
    use CPUTestTrait;

    public function testLARAbsoluteY(): void
    {
        $this->loadProgramToRom([0xBB, 0x10, 0x02, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setRegisterY(0x03);
        $CPU->setMemory(0x0213, 0x11);
        $CPU->run();

        $result = 0x11 & 0xFF;

        $this->assertSame($CPU->getRegisterA(), $result);
        $this->assertSame($CPU->getRegisterX(), $result);
        $this->assertSame($CPU->getSP(), $result);
    }
}
