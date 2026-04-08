<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use App\Type\UInt8;
use PHPUnit\Framework\TestCase;
use Tests\CPUTestTrait;

final class AXSTest extends TestCase
{
    use CPUTestTrait;

    public function testAXS(): void
    {
        $this->loadProgramToRom([0xA9, 0x05, 0xA2, 0x11, 0xCB, 0x34, 0x00]);

        $CPU = $this->getCpu();
        $CPU->run();

        $result = ((0x05 & 0x11) - 0x34 + UInt8::BASE) % UInt8::BASE;

        $this->assertSame($CPU->getRegisterX()->value, $result);
        $this->assertSame($CPU->getFlagZ(), false);
        $this->assertSame($CPU->getFlagN(), $this->getFlagNValue($CPU->getRegisterX()));
    }
}
