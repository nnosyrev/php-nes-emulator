<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use PHPUnit\Framework\TestCase;
use Tests\Integration\CPU\CPUTestTrait;

final class TAYTest extends TestCase
{
    use CPUTestTrait;

    public function testTAY(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA9, 0x05, 0xA8, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->getRegisterY()->value, $CPU->getRegisterA()->value);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterY()));
    }
}
