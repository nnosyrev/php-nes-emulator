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
        $CPU = $this->CPU;
        $CPU->setFlagD(false);
        $CPU->load([0xF8, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->getFlagD(), true);
    }
}
