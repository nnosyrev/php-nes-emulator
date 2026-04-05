<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use App\Type\UInt16;
use App\Type\UInt8;
use PHPUnit\Framework\TestCase;
use Tests\CPUTestTrait;

final class LARTest extends TestCase
{
    use CPUTestTrait;

    public function testLARAbsoluteY(): void
    {
        $this->loadProgramToRom([0xBB, 0x10, 0x02, 0x00]);

        $CPU = $this->getCpu();
        $CPU->setRegisterY(new UInt8(0x03));
        $CPU->setMemory(new UInt16(0x0213), new UInt8(0x11));
        $CPU->run();

        $result = 0x11 & 0xFF;

        $this->assertSame($CPU->getRegisterA()->value, $result);
        $this->assertSame($CPU->getRegisterX()->value, $result);
        $this->assertSame($CPU->getSP()->value, $result);
    }
}
