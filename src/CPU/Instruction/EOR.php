<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;
use App\Util\UInt8;

final class EOR implements InstructionInterface
{
    public function execute(CPU $CPU, ModeInterface $mode): void
    {
        $addr = $mode->getOperandAddress($CPU);

        $data = $CPU->getMemory($addr);

        $CPU->setRegisterA(UInt8::xor($data, $CPU->getRegisterA()));
    }
}
