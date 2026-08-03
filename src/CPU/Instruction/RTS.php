<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;
use App\Util\UInt16;

final class RTS implements InstructionInterface
{
    public function execute(CPU $cpu, ModeInterface $mode): void
    {
        $cpu->getMemory($cpu->getPC(), dummy: true);
        $cpu->endTick();

        $stackValue = $cpu->popFromStackUInt16();

        $cpu->endTick();
        $cpu->endTick();

        $cpu->getMemory($stackValue, dummy: true);

        $cpu->setPC(UInt16::add($stackValue, 1));
    }
}
