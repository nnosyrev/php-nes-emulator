<?php

declare(strict_types=1);

namespace App\CPU\Mode;

use App\CPU\CPU;

final class AbsoluteMode implements ModeInterface
{
    public function getOperandAddress(CPU $cpu): int /* UInt16 */
    {
        $result = $cpu->getMemoryUInt16($cpu->getPC());

        $cpu->endCycle();
        $cpu->endCycle();

        return $result;
    }
}
