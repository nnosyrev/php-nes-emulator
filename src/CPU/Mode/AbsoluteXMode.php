<?php

declare(strict_types=1);

namespace App\CPU\Mode;

use App\CPU\CPU;
use App\Util\UInt16;

final class AbsoluteXMode implements ModeInterface
{
    public function getOperandAddress(CPU $CPU): int /* UInt16 */
    {
        $param = $CPU->getMemoryUInt16($CPU->getPC());

        $CPU->endCycle();
        $CPU->endCycle();

        return UInt16::add($param, $CPU->getRegisterX());
    }
}
