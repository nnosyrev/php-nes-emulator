<?php

declare(strict_types=1);

namespace App\CPU\Mode;

use App\CPU\CPU;
use App\Util\UInt16;

final class AbsoluteYMode implements ModeInterface
{
    public function getOperandAddress(CPU $CPU): int /* UInt16 */
    {
        $param = $CPU->getMemoryUInt16($CPU->getPC());

        return UInt16::add($param, $CPU->getRegisterY());
    }
}
