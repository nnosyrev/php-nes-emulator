<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use PHPUnit\Framework\TestCase;
use Tests\CPUTestTrait;

final class CLCTest extends TestCase
{
    use CPUTestTrait;

    public function testCLC(): void
    {
        $this->loadProgramToRom([0x18, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setFlagC(true);
        $CPU->run();

        $this->assertSame($CPU->getFlagC(), false);
    }
}
