<?php

declare(strict_types=1);

namespace App\CPU\Opcode;

use App\CPU\Instruction\ANDI;
use App\CPU\Instruction\BRK;
use App\CPU\Instruction\DEC;
use App\CPU\Instruction\DEX;
use App\CPU\Instruction\EOR;
use App\CPU\Instruction\INC;
use App\CPU\Instruction\INX;
use App\CPU\Instruction\INY;
use App\CPU\Instruction\LDA;
use App\CPU\Instruction\LDX;
use App\CPU\Instruction\LDY;
use App\CPU\Instruction\ORA;
use App\CPU\Instruction\STA;
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
        $this->add(0x29, ANDI::class, 2, ImmediateMode::class);
        $this->add(0x25, ANDI::class, 2, ZeroPageMode::class);
        $this->add(0x35, ANDI::class, 2, ZeroPageXMode::class);
        $this->add(0x2D, ANDI::class, 3, AbsoluteMode::class);
        $this->add(0x3D, ANDI::class, 3, AbsoluteXMode::class);
        $this->add(0x39, ANDI::class, 3, AbsoluteYMode::class);
        $this->add(0x21, ANDI::class, 2, IndirectXMode::class);
        $this->add(0x31, ANDI::class, 2, IndirectYMode::class);

        $this->add(0x09, ORA::class, 2, ImmediateMode::class);
        $this->add(0x05, ORA::class, 2, ZeroPageMode::class);
        $this->add(0x15, ORA::class, 2, ZeroPageXMode::class);
        $this->add(0x0D, ORA::class, 3, AbsoluteMode::class);
        $this->add(0x1D, ORA::class, 3, AbsoluteXMode::class);
        $this->add(0x19, ORA::class, 3, AbsoluteYMode::class);
        $this->add(0x01, ORA::class, 2, IndirectXMode::class);
        $this->add(0x11, ORA::class, 2, IndirectYMode::class);

        $this->add(0x49, EOR::class, 2, ImmediateMode::class);
        $this->add(0x45, EOR::class, 2, ZeroPageMode::class);
        $this->add(0x55, EOR::class, 2, ZeroPageXMode::class);
        $this->add(0x4D, EOR::class, 3, AbsoluteMode::class);
        $this->add(0x5D, EOR::class, 3, AbsoluteXMode::class);
        $this->add(0x59, EOR::class, 3, AbsoluteYMode::class);
        $this->add(0x41, EOR::class, 2, IndirectXMode::class);
        $this->add(0x51, EOR::class, 2, IndirectYMode::class);

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

        $this->add(0xA0, LDY::class, 2, ImmediateMode::class);
        $this->add(0xA4, LDY::class, 2, ZeroPageMode::class);
        $this->add(0xB4, LDY::class, 2, ZeroPageXMode::class);
        $this->add(0xAC, LDY::class, 3, AbsoluteMode::class);
        $this->add(0xBC, LDY::class, 3, AbsoluteXMode::class);

        $this->add(0xE8, INX::class, 1, NoneMode::class);
        $this->add(0xC8, INY::class, 1, NoneMode::class);

        $this->add(0xCA, DEX::class, 1, NoneMode::class);

        $this->add(0xE6, INC::class, 2, ZeroPageMode::class);
        $this->add(0xF6, INC::class, 2, ZeroPageXMode::class);
        $this->add(0xEE, INC::class, 3, AbsoluteMode::class);
        $this->add(0xFE, INC::class, 3, AbsoluteXMode::class);

        $this->add(0xC6, DEC::class, 2, ZeroPageMode::class);
        $this->add(0xD6, DEC::class, 2, ZeroPageXMode::class);
        $this->add(0xCE, DEC::class, 3, AbsoluteMode::class);
        $this->add(0xDE, DEC::class, 3, AbsoluteXMode::class);

        $this->add(0x85, STA::class, 2, ZeroPageMode::class);
        $this->add(0x95, STA::class, 2, ZeroPageXMode::class);
        $this->add(0x8D, STA::class, 3, AbsoluteMode::class);
        $this->add(0x9D, STA::class, 3, AbsoluteXMode::class);
        $this->add(0x99, STA::class, 3, AbsoluteYMode::class);
        $this->add(0x81, STA::class, 2, IndirectXMode::class);
        $this->add(0x91, STA::class, 2, IndirectYMode::class);

        $this->add(0xAA, TAX::class, 1, NoneMode::class);
        $this->add(0x00, BRK::class, 1, NoneMode::class);
    }
}
