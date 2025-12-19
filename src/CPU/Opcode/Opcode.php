<?php

declare(strict_types=1);

namespace App\CPU\Opcode;

final class Opcode
{
    public function __construct(
        public readonly int $code,
        public readonly string $instructionClass,
        public readonly int $length,
        public readonly string $modeClass,
    ) {}
}
