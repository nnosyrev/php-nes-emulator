<?php

declare(strict_types=1);

namespace App\CPU\Opcode;

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

final class OpcodeCollection
{
    private array $opcodes = [];

    public function __construct()
    {
        $this->setUp();
    }

    private function add(int $code, string $instructionClass, int $length, string $modeClass): void
    {
        if (array_key_exists($code, $this->opcodes)) {
            throw new Exception(sprintf('The "0x%s" opcode already exists', mb_strtoupper(dechex($code))));
        }

        $this->opcodes[$code] = new Opcode($code, $instructionClass, $length, $modeClass);
    }

    public function get(int $code): Opcode
    {
        if (!array_key_exists($code, $this->opcodes)) {
            throw new Exception('Opcode not found');
        }

        return $this->opcodes[$code];
    }

    private function setUp(): void
    {
        $this->add(0xA9, LDA::class, 2, ImmediateMode::class);
        $this->add(0xA5, LDA::class, 2, ZeroPageMode::class);
        $this->add(0xB5, LDA::class, 2, ZeroPageXMode::class);
        $this->add(0xA1, LDA::class, 2, IndirectXMode::class);
        $this->add(0xB1, LDA::class, 2, IndirectYMode::class);
        $this->add(0xAD, LDA::class, 3, AbsoluteMode::class);
        $this->add(0xBD, LDA::class, 3, AbsoluteXMode::class);
        $this->add(0xB9, LDA::class, 3, AbsoluteYMode::class);

        $this->add(0xA2, LDX::class, 2, ImmediateMode::class);
        $this->add(0xA6, LDX::class, 2, ZeroPageMode::class);
        $this->add(0xB6, LDX::class, 2, ZeroPageYMode::class);
        $this->add(0xAE, LDX::class, 3, AbsoluteMode::class);
        $this->add(0xBE, LDX::class, 3, AbsoluteYMode::class);

        $this->add(0xAA, TAX::class, 1, NoneMode::class);
        $this->add(0xE8, INX::class, 1, NoneMode::class);
        $this->add(0x00, BRK::class, 1, NoneMode::class);
    }
}
