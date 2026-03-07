<?php

declare(strict_types=1);

namespace App\CPU\Mode;

use App\CPU\CPU;
use App\Type\UInt16;

final class IndirectYMode implements ModeInterface
{
    public function getOperandAddress(CPU $CPU): UInt16
    {
        $param = $CPU->getMemory($CPU->getPC());

        $ptr = $param->toUInt16();

        $low = $CPU->getMemory($ptr);
        $high = $CPU->getMemory($ptr->increment());

        $result = ($high->value << 8) | $low->value;

        return (new UInt16($result))->add($CPU->getRegisterY());
    }
}
