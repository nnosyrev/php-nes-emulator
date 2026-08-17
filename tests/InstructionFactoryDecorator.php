<?php

declare(strict_types=1);

namespace Tests;

use App\CPU\Instruction\BRK;
use App\CPU\Instruction\InstructionFactory;
use App\CPU\Instruction\InstructionFactoryInterface;
use App\CPU\Instruction\InstructionInterface;
use Tests\BRK as TestsBRK;

final class InstructionFactoryDecorator implements InstructionFactoryInterface
{
    public function __construct(private readonly InstructionFactory $instructionFactory) {}

    public function get(string $class): InstructionInterface
    {
        if ($class === BRK::class) {
            return new TestsBRK;
        }

        return $this->instructionFactory->get($class);
    }
}
