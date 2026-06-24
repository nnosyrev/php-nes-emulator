<?php

declare(strict_types=1);

namespace App\CPU\Mode;

use App\CPU\CPU;

final class ZeroPageMode implements ModeInterface
{
    public function getOperandAddress(CPU $CPU): int /* UInt16 */
    {
        return $CPU->getMemory($CPU->getPC());
    }
}
