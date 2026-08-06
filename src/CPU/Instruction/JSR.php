<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;
use App\Util\UInt16;

final class JSR implements InstructionInterface
{
    public function execute(CPU $cpu, ModeInterface $mode): void
    {
        $pc = $cpu->getPC();

        $low = $cpu->getMemory($pc);
        $cpu->endTick();

        $cpu->getMemory($cpu->getCurrentStackAddr(), dummy: true);
        $cpu->endTick();

        $cpu->pushToStackUInt16(UInt16::subtract(UInt16::add($pc, 2), 1));

        $high = $cpu->getMemory(UInt16::increment($pc));

        $addr = ($high << 8) | $low;

        $cpu->setPC($addr);
    }
}
