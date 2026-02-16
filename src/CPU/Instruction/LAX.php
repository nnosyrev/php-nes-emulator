<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;

final class LAX implements InstructionInterface
{
    public function execute(CPU $CPU, ModeInterface $mode): void
    {
        $addr = $mode->getOperandAddress($CPU);

        $value = $CPU->readMemory($addr);

        $CPU->setRegisterA($value);
        $CPU->setRegisterX($value);
    }
}
