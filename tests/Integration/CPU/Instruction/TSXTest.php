<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use PHPUnit\Framework\TestCase;
use Tests\Integration\CPU\CPUTestTrait;

final class TSXTest extends TestCase
{
    use CPUTestTrait;

    public function testTXS(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xBA, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->getSP()->value, $CPU->getRegisterX()->value);
        $this->assertSame($CPU->getFlagZ(), $CPU->getRegisterX()->value === 0);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterX()));
    }
}
