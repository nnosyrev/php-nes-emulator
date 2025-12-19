<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;

final class LDX
{
    public function execute(CPU $CPU, ModeInterface $mode): void
    {
        $operandAddr = $mode->getOperandAddress($CPU);

        $value = $CPU->readMemory($operandAddr);

        $CPU->setRegisterX($value);
    }
}
