<?php

declare(strict_types=1);

namespace App\CPU\Mode;

use App\CPU\CPU;
use App\Util\UInt8;

final class ZeroPageYMode implements ModeInterface
{
    public function getOperandAddress(CPU $CPU): int /* UInt16 */
    {
        $param = $CPU->getMemory($CPU->getPC());

        return UInt8::add($param, $CPU->getRegisterY());
    }
}
