<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use PHPUnit\Framework\TestCase;
use Tests\Integration\CPU\CPUTestTrait;

final class JSRTest extends TestCase
{
    use CPUTestTrait;

    public function testJSRAbsolute(): void
    {
        $CPU = $this->CPU;
        $CPU->load([0xA9, 0x05, 0x20, 0x07, 0x80, 0xA9, 0x29, 0x00]);
        $CPU->run();

        $this->assertSame($CPU->getRegisterA()->value, 0x05);
        $this->assertSame($CPU->popFromStackUInt16()->value, 0x8004);
    }
}
