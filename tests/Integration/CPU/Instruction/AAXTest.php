<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use PHPUnit\Framework\TestCase;
use Tests\CPUTestTrait;

final class AAXTest extends TestCase
{
    use CPUTestTrait;

    public function testAAXZeroPage(): void
    {
        $this->loadProgramToRom([0xA9, 0x05, 0xA2, 0x11, 0x87, 0x34, 0x00]);

        $CPU = $this->getCpu();
        $CPU->run();

        $this->assertSame($CPU->getMemory(0x34), 0x05 & 0x11);
    }
}
