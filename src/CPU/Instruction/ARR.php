<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;

final class ARR extends RORAbstract
{
    public function execute(CPU $cpu, ModeInterface $mode): void
    {
        $addr = $mode->getOperandAddress($cpu);

        $value = $cpu->getMemory($addr);

        $data = $value->and($cpu->getRegisterA());

        $new = $this->getNew($cpu, $data);

        $cpu->setRegisterA($new);

        $bit5 = ($new->value & 0b100000) === 0b100000;
        $bit6 = ($new->value & 0b1000000) === 0b1000000;

        $cpu->setFlagC($bit6);
        $cpu->setFlagV($bit5 xor $bit6);
    }
}
