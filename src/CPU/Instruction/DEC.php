<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;
use App\Util\UInt8;

final class DEC implements InstructionInterface
{
    public function execute(CPU $CPU, ModeInterface $mode): void
    {
        $addr = $mode->getOperandAddress($CPU);

        $orig = $CPU->getMemory($addr);
        $dec = UInt8::decrement($orig);

        $CPU->setMemory($addr, $dec);

        $CPU->setFlagsZNByValue($dec);
    }
}
