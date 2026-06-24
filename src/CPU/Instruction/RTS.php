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
        //$addr = $cpu->popFromStackUInt16()->add(new UInt8(1));
        $addr = UInt16::add($cpu->popFromStackUInt16(), 1);

        $cpu->setPC($addr);
    }
}
