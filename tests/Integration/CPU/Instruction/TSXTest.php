<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use PHPUnit\Framework\TestCase;
use Tests\CPUTestTrait;

final class TSXTest extends TestCase
{
    use CPUTestTrait;

    public function testTXS(): void
    {
        $this->loadProgramToRom([0xBA, 0x00]);

        $CPU = $this->getCpu();
        $CPU->run();

        $this->assertSame($CPU->getSP()->value, $CPU->getRegisterX()->value);
        $this->assertSame($CPU->getFlagZ(), $CPU->getRegisterX()->value === 0);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterX()));
    }
}
