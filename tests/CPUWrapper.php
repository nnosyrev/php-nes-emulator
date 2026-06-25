<?php

declare(strict_types=1);

namespace Tests;

use App\CPU\CPU;
use App\CPU\Exception\BreakException;

/**
 * @method incrementPC()
 * @method getSP()
 * @method getMemory(int $addr)
 * @method setMemory(int $addr, int $value)
 * @method popFromStack()
 * @method pushToStack(int $data)
 * @method getRegisterA()
 * @method setRegisterA(int $byte)
 * @method getRegisterX()
 * @method setRegisterX(int $byte)
 * @method getRegisterY()
 * @method setRegisterY(int $byte)
 * @method getMemoryUInt16(int $addr)
 * @method setMemoryUInt16(int $addr, int $data)
 * @method getFlagZ()
 * @method setFlagZ(bool $flagZ)
 * @method getFlagN()
 * @method setFlagN(bool $flagN)
 * @method getFlagC()
 * @method setFlagC(bool $flagC)
 * @method getFlagI()
 * @method setFlagI(bool $flagI)
 * @method getFlagD()
 * @method setFlagD(bool $flagD)
 * @method getFlagV()
 * @method setFlagV(bool $flagV)
 * @method getFlagB()
 * @method setFlagB(bool $flagB)
 * @method pushToStackUInt16(int $value)
 * @method popFromStackUInt16()
 */
final class CPUWrapper
{
    public function __construct(private readonly CPU $cpu) {}

    public function run(): void
    {
        try {
            $this->cpu->run();
        } catch (BreakException) {
            return;
        }
    }

    public function __call($name, $arguments)
    {
        return $this->cpu->$name(...$arguments);
    }
}
