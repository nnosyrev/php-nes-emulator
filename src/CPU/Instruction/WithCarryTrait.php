<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use App\CPU\CPU;
use App\Type\UInt8;

trait WithCarryTrait
{
    public function addToRegisterAWithCarry(int /* UInt8 */ $data, CPU $cpu): void
    {
        $result = $cpu->getRegisterA() + $data;

        if ($cpu->getFlagC()) {
            $result = $result + 1;
        }

        $cpu->setFlagC($result > 0xFF);

        $result = $result % UInt8::BASE;

        $condition = ($data ^ $result) & ($result ^ $cpu->getRegisterA()) & 0x80;

        $cpu->setFlagV($condition !== 0);

        $cpu->setRegisterA($result);
    }
}
