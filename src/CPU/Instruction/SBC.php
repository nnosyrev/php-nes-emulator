<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;
use App\UInt8;

final class SBC implements InstructionInterface
{
    public function execute(CPU $cpu, ModeInterface $mode): void
    {
        $addr = $mode->getOperandAddress($cpu);

        $data = $cpu->readMemory($addr);

        $result = $cpu->getRegisterA()->subtract($data);

        if (!$cpu->getFlagC()) {
            $result = $result->subtract(new UInt8(1));
        }

        $cpu->setFlagC(!($result->value > 0xFF));

        $condition = $data->xor($result)->and($result->xor($cpu->getRegisterA())->and(new UInt8(0x80)));
        $cpu->setFlagV($condition->value !== 0);

        $cpu->setRegisterA($result);
    }
}
