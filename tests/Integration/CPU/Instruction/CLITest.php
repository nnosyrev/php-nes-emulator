<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use PHPUnit\Framework\TestCase;
use Tests\CPUTestTrait;

final class CLITest extends TestCase
{
    use CPUTestTrait;

    public function testCLI(): void
    {
        $this->loadProgramToRom([0x58, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setFlagI(true);
        $CPU->run();

        $this->assertSame($CPU->getFlagI(), false);
    }
}
