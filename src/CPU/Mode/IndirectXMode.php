<?php

declare(strict_types=1);

namespace App\CPU\Mode;

use App\CPU\CPU;
use App\UInt16;

final class IndirectXMode implements ModeInterface
{
    public function getOperandAddress(CPU $CPU): UInt16
    {
        $param = $CPU->readMemory($CPU->getPC());

        $ptr = $param->add($CPU->getRegisterX())->toUInt16();

        $low = $CPU->readMemory($ptr);
        $high = $CPU->readMemory($ptr->increment());

        $result = ($high->value << 8) | $low->value;

        return new UInt16($result);
    }
}
