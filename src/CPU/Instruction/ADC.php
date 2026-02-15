<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;

final class ADC implements InstructionInterface
{
    use WithCarryTrait;

    public function execute(CPU $cpu, ModeInterface $mode): void
    {
        $addr = $mode->getOperandAddress($cpu);

        $data = $cpu->readMemory($addr);

        $this->doWithCarry($data, $cpu);
    }
}
