<?php

declare(strict_types=1);

namespace Tests\Integration\CPU;

use PHPUnit\Framework\TestCase;

final class CPUTest extends TestCase
{
    use CPUTestTrait;

    public function test5opcodes(): void
    {
        $this->loadProgramToRom([0xA9, 0xC0, 0xAA, 0xE8, 0x00]);

        $CPU = $this->getCpu();
        $CPU->run();

        $this->assertSame($CPU->getRegisterX()->value, 0xC1);
    }
}
