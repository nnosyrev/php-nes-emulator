<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\CPU\Mode\ModeInterface;

final class ROL extends ROLAbstract
{
    public function execute(CPU $CPU, ModeInterface $mode): void
    {
        $addr = $mode->getOperandAddress($CPU);

        $old = $CPU->getMemory($addr);

        $new = $this->getNew($CPU, $old);

        $this->setFlagC($CPU, $old);

        $CPU->setFlagsZNByValue($new);
        $CPU->setMemory($addr, $new);
    }
}
