<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use PHPUnit\Framework\TestCase;
use Tests\Integration\CPU\CPUTestTrait;

final class CLDTest extends TestCase
{
    use CPUTestTrait;

    public function testCLD(): void
    {
        $CPU = $this->CPU;
        $CPU->setFlagD(true);
        $CPU->load([0xD8, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->getFlagD(), false);
    }
}
