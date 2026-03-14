<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use PHPUnit\Framework\TestCase;
use Tests\CPUTestTrait;

final class CLDTest extends TestCase
{
    use CPUTestTrait;

    public function testCLD(): void
    {
        $this->loadProgramToRom([0xD8, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setFlagD(true);
        $CPU->run();

        $this->assertSame($CPU->getFlagD(), false);
    }
}
