<?php

declare(strict_types=1);

namespace App\CPU\Mode;

use App\CPU\CPU;
use App\Util\UInt16;

final class IndirectYMode implements ModeInterface
{
    public function getOperandAddress(CPU $CPU): int /* UInt16 */
    {
        $param = $CPU->getMemory($CPU->getPC());

        $ptr = $param;

        $low = $CPU->getMemory($ptr);
        $high = $CPU->getMemory(UInt16::increment($ptr));

        $result = ($high << 8) | $low;

        return UInt16::add($result, $CPU->getRegisterY());
    }
}
