<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use PHPUnit\Framework\TestCase;
use Tests\Integration\CPU\CPUTestTrait;

final class SECTest extends TestCase
{
    use CPUTestTrait;

    public function testSEC(): void
    {
        $this->loadProgramToRom([0x38, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setFlagC(false);
        $CPU->run();

        $this->assertSame($CPU->getFlagC(), true);
    }
}
