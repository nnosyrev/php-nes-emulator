<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;

final class EOR implements InstructionInterface
{
    public function execute(CPU $CPU, ModeInterface $mode): void
    {
        $addr = $mode->getOperandAddress($CPU);

        $data = $CPU->readMemory($addr);

        $CPU->setRegisterA($data->xor($CPU->getRegisterA()));
    }
}
