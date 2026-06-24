<?php

declare(strict_types=1);

namespace App\CPU\Mode;

use App\CPU\CPU;

interface ModeInterface
{
    public function getOperandAddress(CPU $CPU): int /* UInt16 */;
}
