<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use PHPUnit\Framework\TestCase;
use Tests\CPUTestTrait;

final class AXSTest extends TestCase
{
    use CPUTestTrait;

    public function testAXS(): void
    {
        $this->loadProgramToRom([0xA9, 0xFF, 0xA2, 0xF1, 0xCB, 0x34, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setFlagC(false);
        $CPU->run();

        $result = (0xFF & 0xF1) - 0x34;

        $this->assertSame($CPU->getRegisterX(), $result);
        $this->assertSame($CPU->getFlagZ(), false);
        // @phpstan-ignore greaterOrEqual.alwaysTrue
        $this->assertSame($CPU->getFlagC(), (0xFF & 0xF1) >= 0x34);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterX()));
    }
}
