<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\Instruction;

use PHPUnit\Framework\TestCase;
use Tests\Integration\CPU\CPUTestTrait;

final class TXSTest extends TestCase
{
    use CPUTestTrait;

    public function testTXS(): void
    {
        $this->loadProgramToRom([0xA2, 0x05, 0x9A, 0x00]);

        $CPU = $this->getCpu();
        $CPU->run();

        $this->assertSame($CPU->getSP()->value, $CPU->getRegisterX()->value);
        $this->assertSame($CPU->getSP()->value, 0x05);
    }
}
