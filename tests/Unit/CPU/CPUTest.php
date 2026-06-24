<?php

declare(strict_types=1);

namespace Tests\Unit\CPU;

use App\Type\UInt16;
use App\Type\UInt8;
use PHPUnit\Framework\TestCase;
use Tests\CPUTestTrait;

final class CPUTest extends TestCase
{
    use CPUTestTrait;

    public function testReadWriteMemoryUInt16(): void
    {
        $this->loadProgramToRom([]);

        $addr = 0;

        $CPU = $this->getCpu();
        $CPU->setMemoryUInt16($addr, 0x8000);

        $readed = $CPU->getMemoryUInt16($addr);

        $this->assertSame($readed, 0x8000);
    }

    public function testPushPopStack(): void
    {
        $this->loadProgramToRom([]);

        $CPU = $this->getCpu();
        $CPU->pushToStack(1);
        $CPU->pushToStack(2);
        $CPU->pushToStack(3);

        $this->assertSame($CPU->popFromStack(), 3);
        $this->assertSame($CPU->popFromStack(), 2);
        $this->assertSame($CPU->popFromStack(), 1);
    }
}
