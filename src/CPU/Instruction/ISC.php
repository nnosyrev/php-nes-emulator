<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;
use App\Util\UInt8;

final class ISC implements InstructionInterface
{
    use WithCarryTrait;

    public function execute(CPU $cpu, ModeInterface $mode): void
    {
        $addr = $mode->getOperandAddress($cpu);

        $old = $cpu->getMemory($addr);

        $new = UInt8::increment($old);

        $cpu->setMemory($addr, $new);

        $this->addToRegisterAWithCarry(UInt8::xor($new, 0xFF), $cpu);
    }
}
