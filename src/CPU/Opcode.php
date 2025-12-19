<?php

declare(strict_types=1);

namespace App\CPU;

use Exception;

final class Opcode
{
    private static array $opcodes = [];

    public function __construct(
        private int $code,
        private string $instructionClass,
        private int $length,
        private string $modeClass
    ) {
    }

    public function getinstructionClass(): string
    {
        return $this->instructionClass;
    }

    public function getLenght(): int
    {
        return $this->length;
    }

    public function getModeClass(): string
    {
        return $this->modeClass;
    }

    public static function add(int $code, string $instructionClass, int $length, string $modeClass): void
    {
        if (array_key_exists($code, self::$opcodes)) {
            throw new Exception(sprintf('The "0x%s" opcode already exists', mb_strtoupper(dechex($code))));
        }

        self::$opcodes[$code] = new self($code, $instructionClass, $length, $modeClass);
    }

    public static function get(int $code): self
    {
        if (!array_key_exists($code, self::$opcodes)) {
            throw new Exception('Opcode not found');
        }

        return self::$opcodes[$code];
    }
}

