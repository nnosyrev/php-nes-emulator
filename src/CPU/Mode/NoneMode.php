<?php

declare(strict_types=1);

namespace App\CPU\Mode;

use App\CPU\CPU;
use App\Type\UInt16;

final class NoneMode implements ModeInterface
{
    public function getOperandAddress(CPU $CPU): UInt16
    {
        throw new \Exception('Something went wrong.');
    }
}
