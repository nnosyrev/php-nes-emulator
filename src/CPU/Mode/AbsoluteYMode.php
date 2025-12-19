<?php

declare(strict_types=1);

namespace App\CPU\Mode;

use App\CPU\CPU;
use App\UInt16;

final class AbsoluteYMode implements ModeInterface
{
    public function getOperandAddress(CPU $CPU): UInt16
    {
        $param = $CPU->readMemoryUInt16($CPU->getPC());

        return $param->add($CPU->getRegisterY());
    }
}
