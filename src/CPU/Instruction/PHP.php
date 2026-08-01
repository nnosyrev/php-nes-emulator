<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;
use App\Util\UInt8;

final class PHP implements InstructionInterface
{
    public function execute(CPU $CPU, ModeInterface $mode): void
    {
        // Dummy read
        $CPU->getMemory($CPU->getPC(), dummy: true);
        $CPU->endTick();

        // Setting B flag
        $flags = $CPU->getFlagsAsUInt8();
        $flags = UInt8::or($flags, CPU::FLAG_B);

        $CPU->pushToStack($flags);
    }
}
