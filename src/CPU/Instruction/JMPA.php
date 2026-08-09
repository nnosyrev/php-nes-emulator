<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;

final class JMPA implements InstructionInterface
{
    public function execute(CPU $cpu, ModeInterface $mode): void
    {
        $addr = $cpu->getMemoryUInt16($cpu->getPC());

        $cpu->endTick();

        $cpu->setPC($addr);
    }
}
