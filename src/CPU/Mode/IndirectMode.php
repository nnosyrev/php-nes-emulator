<?php

declare(strict_types=1);

namespace App\CPU\Mode;

use App\CPU\CPU;

final class IndirectMode implements ModeInterface
{
    public function getOperandAddress(CPU $CPU): int /* UInt16 */
    {
        $addr = $CPU->getMemoryUInt16($CPU->getPC());

        if (($addr & 0x00FF) === 0x00FF) {
            $low = $CPU->getMemory($addr);
            $high = $CPU->getMemory($addr & 0xFF00);

            $result = ($high << 8) | $low;

            return $result;
        }

        return $CPU->getMemoryUInt16($addr);
    }
}
