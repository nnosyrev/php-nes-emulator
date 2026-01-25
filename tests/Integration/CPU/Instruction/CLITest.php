<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use PHPUnit\Framework\TestCase;
use Tests\Integration\CPU\CPUTestTrait;

final class CLITest extends TestCase
{
    use CPUTestTrait;

    public function testCLI(): void
    {
        $CPU = $this->CPU;
        $CPU->setFlagI(true);
        $CPU->load([0x58, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->getFlagI(), false);
    }
}
