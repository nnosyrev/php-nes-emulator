<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;
use App\UInt8;

final class RTS implements InstructionInterface
{
    public function execute(CPU $cpu, ModeInterface $mode): void
    {
        $addr = $cpu->popFromStackUInt16()->add(new UInt8(1));

        $cpu->setPC($addr);
    }
}
