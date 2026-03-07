<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;
use App\Type\UInt8;

final class SBC implements InstructionInterface
{
    use WithCarryTrait;

    public function execute(CPU $cpu, ModeInterface $mode): void
    {
        $addr = $mode->getOperandAddress($cpu);

        $data = $cpu->getMemory($addr);

        $this->doWithCarry($data->xor(new UInt8(0xFF)), $cpu);
    }
}
