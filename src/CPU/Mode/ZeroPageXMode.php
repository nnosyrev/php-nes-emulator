<?php

declare(strict_types=1);

namespace App\CPU\Mode;

use App\CPU\CPU;
use App\Util\UInt8;

final class ZeroPageXMode implements ModeInterface
{
    public function getOperandAddress(CPU $CPU): int /* UInt16 */
    {
        return UInt8::add($CPU->getMemory($CPU->getPC()), $CPU->getRegisterX());
    }
}
