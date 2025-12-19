<?php

declare(strict_types=1);

namespace App\CPU\Mode;

use App\CPU\CPU;
use App\UInt16;

interface ModeInterface
{
    public function getOperandAddress(CPU $CPU): UInt16;
}
