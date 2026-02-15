<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\UInt8;

trait WithCarryTrait
{
    public function doWithCarry(UInt8 $data, CPU $cpu): void
    {
        $result = $cpu->getRegisterA()->value + $data->value;

        if ($cpu->getFlagC()) {
            $result = $result + 1;
        }

        $cpu->setFlagC($result > 0xFF);

        $result = $result % 256;

        $condition = ($data->value ^ $result) & ($result ^ $cpu->getRegisterA()->value) & 0x80;

        $cpu->setFlagV($condition !== 0);

        $cpu->setRegisterA(new UInt8($result));
    }
}
