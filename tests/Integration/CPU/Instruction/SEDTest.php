<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use PHPUnit\Framework\TestCase;
use Tests\Integration\CPU\CPUTestTrait;

final class SEDTest extends TestCase
{
    use CPUTestTrait;

    public function testSED(): void
    {
        $this->loadProgramToRom([0xF8, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setFlagD(false);
        $CPU->run();

        $this->assertSame($CPU->getFlagD(), true);
    }
}
