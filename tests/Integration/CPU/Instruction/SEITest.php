<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use PHPUnit\Framework\TestCase;
use Tests\Integration\CPU\CPUTestTrait;

final class SEITest extends TestCase
{
    use CPUTestTrait;

    public function testSEI(): void
    {
        $this->loadProgramToRom([0x78, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setFlagI(false);
        $CPU->run();

        $this->assertSame($CPU->getFlagI(), true);
    }
}
