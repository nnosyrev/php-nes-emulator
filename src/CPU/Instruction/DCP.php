<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;
use App\Util\UInt8;

final class DCP implements InstructionInterface
{
    public function execute(CPU $cpu, ModeInterface $mode): void
    {
        $addr = $mode->getOperandAddress($cpu);

        $value = $cpu->getMemory($addr);

        $result = UInt8::decrement($value);

        $cpu->setMemory($addr, $result);

        $cpu->setFlagC($cpu->getRegisterA() >= $result);
        $cpu->setFlagsZNByValue(UInt8::subtract($cpu->getRegisterA(), $result));
    }
}
