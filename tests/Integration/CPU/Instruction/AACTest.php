<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use PHPUnit\Framework\TestCase;
use Tests\Integration\CPU\CPUTestTrait;

final class AACTest extends TestCase
{
    use CPUTestTrait;

    public function testAAC(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA9, 0x05, 0x0B, 0xA6, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->getRegisterA()->value, 0x05 & 0xA6);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterA()));
        $this->assertSame($CPU->getFlagC(), $this->getFlagNValue($CPU->getRegisterA()));
    }
}
