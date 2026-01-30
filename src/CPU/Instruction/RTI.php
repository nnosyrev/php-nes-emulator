<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;

final class RTI implements InstructionInterface
{
    public function execute(CPU $cpu, ModeInterface $mode): void
    {
        $cpu->setFlagsFromUInt8($cpu->popFromStack());

        $cpu->setPC($cpu->popFromStackUInt16());
    }
}
