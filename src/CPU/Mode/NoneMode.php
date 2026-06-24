<?php

declare(strict_types=1);

namespace App\CPU\Mode;

use App\CPU\CPU;

final class NoneMode implements ModeInterface
{
    public function getOperandAddress(CPU $CPU): int /* UInt16 */
    {
        throw new \Exception('Something went wrong.');
    }
}
