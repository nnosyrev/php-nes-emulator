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

        $addr = new UInt16(0);

        $CPU = $this->getCpu();
        $CPU->setMemoryUInt16($addr, new UInt16(0x8000));

        $readed = $CPU->getMemoryUInt16($addr);

        $this->assertSame($readed->value, 0x8000);
    }

    public function testPushPopStack(): void
    {
        $this->loadProgramToRom([]);

        $CPU = $this->getCpu();
        $CPU->pushToStack(new UInt8(1));
        $CPU->pushToStack(new UInt8(2));
        $CPU->pushToStack(new UInt8(3));

        $this->assertSame($CPU->popFromStack()->value, 3);
        $this->assertSame($CPU->popFromStack()->value, 2);
        $this->assertSame($CPU->popFromStack()->value, 1);
    }
}
