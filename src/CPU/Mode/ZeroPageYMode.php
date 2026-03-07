<?php

declare(strict_types=1);

namespace App\CPU\Mode;

use App\CPU\CPU;
use App\Type\UInt16;

final class ZeroPageYMode implements ModeInterface
{
    public function getOperandAddress(CPU $CPU): UInt16
    {
        $param = $CPU->getMemory($CPU->getPC());

        return $param
            ->add($CPU->getRegisterY())
            ->toUInt16();
    }
}
