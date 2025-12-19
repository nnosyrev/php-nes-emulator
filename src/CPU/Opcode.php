<?php

declare(strict_types=1);

namespace App\CPU;

use App\CPU\Instruction\BRK;
use App\CPU\Instruction\INX;
use App\CPU\Instruction\LDA;
use App\CPU\Instruction\LDX;
use App\CPU\Instruction\TAX;
use App\CPU\Mode\AbsoluteMode;
use App\CPU\Mode\AbsoluteXMode;
use App\CPU\Mode\AbsoluteYMode;
use App\CPU\Mode\ImmediateMode;
use App\CPU\Mode\IndirectXMode;
use App\CPU\Mode\IndirectYMode;
use App\CPU\Mode\NoneMode;
use App\CPU\Mode\ZeroPageMode;
use App\CPU\Mode\ZeroPageXMode;
use App\CPU\Mode\ZeroPageYMode;
use Exception;

final class Opcode
{
    private static array $opcodes = [];

    public function __construct(
        private int $code,
        private string $instructionClass,
        private int $length,
        private string $modeClass,
    ) {}

    public function getInstructionClass(): string
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

    private static function add(int $code, string $instructionClass, int $length, string $modeClass): void
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

    public static function setUp(): void
    {
        Opcode::add(0xA9, LDA::class, 2, ImmediateMode::class);
        Opcode::add(0xA5, LDA::class, 2, ZeroPageMode::class);
        Opcode::add(0xB5, LDA::class, 2, ZeroPageXMode::class);
        Opcode::add(0xA1, LDA::class, 2, IndirectXMode::class);
        Opcode::add(0xB1, LDA::class, 2, IndirectYMode::class);
        Opcode::add(0xAD, LDA::class, 3, AbsoluteMode::class);
        Opcode::add(0xBD, LDA::class, 3, AbsoluteXMode::class);
        Opcode::add(0xB9, LDA::class, 3, AbsoluteYMode::class);

        Opcode::add(0xA2, LDX::class, 2, ImmediateMode::class);
        Opcode::add(0xA6, LDX::class, 2, ZeroPageMode::class);
        Opcode::add(0xB6, LDX::class, 2, ZeroPageYMode::class);
        Opcode::add(0xAE, LDX::class, 3, AbsoluteMode::class);
        Opcode::add(0xBE, LDX::class, 3, AbsoluteYMode::class);

        Opcode::add(0xAA, TAX::class, 1, NoneMode::class);
        Opcode::add(0xE8, INX::class, 1, NoneMode::class);
        Opcode::add(0x00, BRK::class, 1, NoneMode::class);
    }
}
