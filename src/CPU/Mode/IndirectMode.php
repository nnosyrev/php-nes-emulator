<?php

declare(strict_types=1);

namespace App\CPU\Mode;

use App\CPU\CPU;
use App\UInt16;

final class IndirectMode implements ModeInterface
{
    public function getOperandAddress(CPU $CPU): UInt16
    {
        $addr = $CPU->readMemoryUInt16($CPU->getPC());

        if (($addr->value & 0x00FF) === 0x00FF) {
            $low = $CPU->readMemory($addr);
            $high = $CPU->readMemory(new UInt16($addr->value & 0xFF00));

            $result = ($high->value << 8) | $low->value;

            return new UInt16($result);
        }

        return $CPU->readMemoryUInt16($addr);
    }
}
