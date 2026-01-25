<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use PHPUnit\Framework\TestCase;
use Tests\Integration\CPU\CPUTestTrait;

final class CLVTest extends TestCase
{
    use CPUTestTrait;

    public function testCLV(): void
    {
        $CPU = $this->CPU;
        $CPU->setFlagV(true);
        $CPU->load([0xB8, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->getFlagV(), false);
    }
}
