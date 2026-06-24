<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use PHPUnit\Framework\TestCase;
use Tests\CPUTestTrait;

final class XASTest extends TestCase
{
    use CPUTestTrait;

    public function testXASAbsoluteY(): void
    {
        $this->loadProgramToRom([0xA9, 0x05, 0xA2, 0x11, 0xA0, 0x03, 0x9B, 0x10, 0x02, 0x00]);

        $CPU = $this->getCpu();
        $CPU->run();

        $sp = 0x05 & 0x11;

        $this->assertSame($CPU->getSP(), $sp);
        $this->assertSame($CPU->getMemory(0x0213), (0x02 + 1) & $sp);
    }
}
