<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use Exception;

final class InstructionFactory implements InstructionFactoryInterface
{
    private array $instructions = [];

    public function get(string $class): InstructionInterface
    {
        if (!\array_key_exists($class, $this->instructions)) {
            $instruction = $this->create($class);

            if (!($instruction instanceof InstructionInterface)) {
                throw new Exception('Incorrect instruction class');
            }

            $this->instructions[$class] = $instruction;
        }

        return $this->instructions[$class];
    }

    private function create(string $class): InstructionInterface
    {
        return new $class();
    }
}
