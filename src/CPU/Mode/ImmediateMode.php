<?php

declare(strict_types=1);

namespace App\CPU\Mode;

use App\CPU\CPU;

final class ImmediateMode implements ModeInterface
{
    public function getOperandAddress(CPU $CPU): int /* UInt16 */
    {
        return $CPU->getPC();
    }
}
