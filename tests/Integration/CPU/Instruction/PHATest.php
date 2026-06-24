<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use PHPUnit\Framework\TestCase;
use Tests\CPUTestTrait;

final class PHATest extends TestCase
{
    use CPUTestTrait;

    public function testPHA(): void
    {
        $this->loadProgramToRom([0xA9, 0x05, 0x48, 0x00]);

        $CPU = $this->getCpu();
        $CPU->run();

        $stackValue = $CPU->popFromStack();

        $this->assertSame($stackValue, $CPU->getRegisterA());
        $this->assertSame($stackValue, 0x05);
    }
}
