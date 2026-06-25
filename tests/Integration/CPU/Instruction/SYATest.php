<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use PHPUnit\Framework\TestCase;
use Tests\CPUTestTrait;

final class SYATest extends TestCase
{
    use CPUTestTrait;

    public function testSYA(): void
    {
        $this->loadProgramToRom([0xA0, 0x05, 0xA2, 0x03, 0x9C, 0x20, 0x01, 0x00]);

        $CPU = $this->getCpu();
        $CPU->run();

        $this->assertSame($CPU->getMemory(0x0123), 0x05 & (0x01 + 1));
    }
}
