<?php

declare(strict_types=1);

namespace App\CPU\Instruction;

use Exception;

final class InstructionFactory
{
    private array $instructions = [];

    public function make(string $class): InstructionInterface
    {
        if (!array_key_exists($class, $this->instructions)) {
            $instruction = new $class();

            if (!($instruction instanceof InstructionInterface)) {
                throw new Exception('Incorrect instruction class');
            }

            $this->instructions[$class] = $instruction;
        }

        return $this->instructions[$class];
    }
}
