<?php

declare(strict_types=1);

namespace App\CPU\Opcode;

use App\CPU\Instruction\AAC;
use App\CPU\Instruction\AAX;
use App\CPU\Instruction\ADC;
use App\CPU\Instruction\ANDI;
use App\CPU\Instruction\ASL;
use App\CPU\Instruction\ASLA;
use App\CPU\Instruction\BCC;
use App\CPU\Instruction\BCS;
use App\CPU\Instruction\BEQ;
use App\CPU\Instruction\BIT;
use App\CPU\Instruction\BMI;
use App\CPU\Instruction\BNE;
use App\CPU\Instruction\BPL;
use App\CPU\Instruction\BRK;
use App\CPU\Instruction\BVC;
use App\CPU\Instruction\BVS;
use App\CPU\Instruction\CLC;
use App\CPU\Instruction\CLD;
use App\CPU\Instruction\CLI;
use App\CPU\Instruction\CLV;
use App\CPU\Instruction\CMP;
use App\CPU\Instruction\CPX;
use App\CPU\Instruction\CPY;
use App\CPU\Instruction\DEC;
use App\CPU\Instruction\DEX;
use App\CPU\Instruction\DEY;
use App\CPU\Instruction\DOP;
use App\CPU\Instruction\EOR;
use App\CPU\Instruction\INC;
use App\CPU\Instruction\INX;
use App\CPU\Instruction\INY;
use App\CPU\Instruction\JMP;
use App\CPU\Instruction\JSR;
use App\CPU\Instruction\LAX;
use App\CPU\Instruction\LDA;
use App\CPU\Instruction\LDX;
use App\CPU\Instruction\LDY;
use App\CPU\Instruction\LSR;
use App\CPU\Instruction\LSRA;
use App\CPU\Instruction\NOP;
use App\CPU\Instruction\ORA;
use App\CPU\Instruction\PHA;
use App\CPU\Instruction\PHP;
use App\CPU\Instruction\PLA;
use App\CPU\Instruction\PLP;
use App\CPU\Instruction\ROL;
use App\CPU\Instruction\ROLA;
use App\CPU\Instruction\ROR;
use App\CPU\Instruction\RORA;
use App\CPU\Instruction\RTI;
use App\CPU\Instruction\RTS;
use App\CPU\Instruction\SBC;
use App\CPU\Instruction\SEC;
use App\CPU\Instruction\SED;
use App\CPU\Instruction\SEI;
use App\CPU\Instruction\SRE;
use App\CPU\Instruction\STA;
use App\CPU\Instruction\STX;
use App\CPU\Instruction\STY;
use App\CPU\Instruction\TAX;
use App\CPU\Instruction\TAY;
use App\CPU\Instruction\TOP;
use App\CPU\Instruction\TSX;
use App\CPU\Instruction\TXA;
use App\CPU\Instruction\TXS;
use App\CPU\Instruction\TYA;
use App\CPU\Mode\AbsoluteMode;
use App\CPU\Mode\AbsoluteXMode;
use App\CPU\Mode\AbsoluteYMode;
use App\CPU\Mode\ImmediateMode;
use App\CPU\Mode\IndirectMode;
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
        $this->add(0x88, DEY::class, 1, NoneMode::class);

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

        $this->add(0x86, STX::class, 2, ZeroPageMode::class);
        $this->add(0x96, STX::class, 2, ZeroPageYMode::class);
        $this->add(0x8E, STX::class, 3, AbsoluteMode::class);

        $this->add(0x84, STY::class, 2, ZeroPageMode::class);
        $this->add(0x94, STY::class, 2, ZeroPageXMode::class);
        $this->add(0x8C, STY::class, 3, AbsoluteMode::class);

        $this->add(0xAA, TAX::class, 1, NoneMode::class);
        $this->add(0xA8, TAY::class, 1, NoneMode::class);
        $this->add(0x8A, TXA::class, 1, NoneMode::class);
        $this->add(0x98, TYA::class, 1, NoneMode::class);

        $this->add(0x00, BRK::class, 1, NoneMode::class);

        $this->add(0x18, CLC::class, 1, NoneMode::class);
        $this->add(0xD8, CLD::class, 1, NoneMode::class);
        $this->add(0x58, CLI::class, 1, NoneMode::class);
        $this->add(0xB8, CLV::class, 1, NoneMode::class);

        $this->add(0x38, SEC::class, 1, NoneMode::class);
        $this->add(0xF8, SED::class, 1, NoneMode::class);
        $this->add(0x78, SEI::class, 1, NoneMode::class);

        $this->add(0x9A, TXS::class, 1, NoneMode::class);
        $this->add(0xBA, TSX::class, 1, NoneMode::class);

        $this->add(0x68, PLA::class, 1, NoneMode::class);
        $this->add(0x48, PHA::class, 1, NoneMode::class);

        $this->add(0xEA, NOP::class, 1, NoneMode::class);

        $this->add(0x2A, ROLA::class, 1, NoneMode::class);
        $this->add(0x26, ROL::class, 2, ZeroPageMode::class);
        $this->add(0x36, ROL::class, 2, ZeroPageXMode::class);
        $this->add(0x2E, ROL::class, 3, AbsoluteMode::class);
        $this->add(0x3E, ROL::class, 3, AbsoluteXMode::class);

        $this->add(0x6A, RORA::class, 1, NoneMode::class);
        $this->add(0x66, ROR::class, 2, ZeroPageMode::class);
        $this->add(0x76, ROR::class, 2, ZeroPageXMode::class);
        $this->add(0x6E, ROR::class, 3, AbsoluteMode::class);
        $this->add(0x7E, ROR::class, 3, AbsoluteXMode::class);

        $this->add(0x90, BCC::class, 2, NoneMode::class);
        $this->add(0xB0, BCS::class, 2, NoneMode::class);
        $this->add(0xF0, BEQ::class, 2, NoneMode::class);
        $this->add(0xD0, BNE::class, 2, NoneMode::class);
        $this->add(0x50, BVC::class, 2, NoneMode::class);
        $this->add(0x70, BVS::class, 2, NoneMode::class);
        $this->add(0x30, BMI::class, 2, NoneMode::class);
        $this->add(0x10, BPL::class, 2, NoneMode::class);

        $this->add(0xC9, CMP::class, 2, ImmediateMode::class);
        $this->add(0xC5, CMP::class, 2, ZeroPageMode::class);
        $this->add(0xD5, CMP::class, 2, ZeroPageXMode::class);
        $this->add(0xCD, CMP::class, 3, AbsoluteMode::class);
        $this->add(0xDD, CMP::class, 3, AbsoluteXMode::class);
        $this->add(0xD9, CMP::class, 3, AbsoluteYMode::class);
        $this->add(0xC1, CMP::class, 2, IndirectXMode::class);
        $this->add(0xD1, CMP::class, 2, IndirectYMode::class);

        $this->add(0xE0, CPX::class, 2, ImmediateMode::class);
        $this->add(0xE4, CPX::class, 2, ZeroPageMode::class);
        $this->add(0xEC, CPX::class, 3, AbsoluteMode::class);

        $this->add(0xC0, CPY::class, 2, ImmediateMode::class);
        $this->add(0xC4, CPY::class, 2, ZeroPageMode::class);
        $this->add(0xCC, CPY::class, 3, AbsoluteMode::class);

        $this->add(0x08, PHP::class, 1, NoneMode::class);
        $this->add(0x28, PLP::class, 1, NoneMode::class);

        $this->add(0x0A, ASLA::class, 1, NoneMode::class);
        $this->add(0x06, ASL::class, 2, ZeroPageMode::class);
        $this->add(0x16, ASL::class, 2, ZeroPageXMode::class);
        $this->add(0x0E, ASL::class, 3, AbsoluteMode::class);
        $this->add(0x1E, ASL::class, 3, AbsoluteXMode::class);

        $this->add(0x4A, LSRA::class, 1, NoneMode::class);
        $this->add(0x46, LSR::class, 2, ZeroPageMode::class);
        $this->add(0x56, LSR::class, 2, ZeroPageXMode::class);
        $this->add(0x4E, LSR::class, 3, AbsoluteMode::class);
        $this->add(0x5E, LSR::class, 3, AbsoluteXMode::class);

        $this->add(0x4C, JMP::class, 3, AbsoluteMode::class);
        $this->add(0x6C, JMP::class, 3, IndirectMode::class);

        $this->add(0x20, JSR::class, 3, AbsoluteMode::class);
        $this->add(0x60, RTS::class, 1, NoneMode::class);

        $this->add(0x24, BIT::class, 2, ZeroPageMode::class);
        $this->add(0x2C, BIT::class, 3, AbsoluteMode::class);

        $this->add(0x69, ADC::class, 2, ImmediateMode::class);
        $this->add(0x65, ADC::class, 2, ZeroPageMode::class);
        $this->add(0x75, ADC::class, 2, ZeroPageXMode::class);
        $this->add(0x6D, ADC::class, 3, AbsoluteMode::class);
        $this->add(0x7D, ADC::class, 3, AbsoluteXMode::class);
        $this->add(0x79, ADC::class, 3, AbsoluteYMode::class);
        $this->add(0x61, ADC::class, 2, IndirectXMode::class);
        $this->add(0x71, ADC::class, 2, IndirectYMode::class);

        $this->add(0xE9, SBC::class, 2, ImmediateMode::class);
        $this->add(0xE5, SBC::class, 2, ZeroPageMode::class);
        $this->add(0xF5, SBC::class, 2, ZeroPageXMode::class);
        $this->add(0xED, SBC::class, 3, AbsoluteMode::class);
        $this->add(0xFD, SBC::class, 3, AbsoluteXMode::class);
        $this->add(0xF9, SBC::class, 3, AbsoluteYMode::class);
        $this->add(0xE1, SBC::class, 2, IndirectXMode::class);
        $this->add(0xF1, SBC::class, 2, IndirectYMode::class);

        $this->add(0x40, RTI::class, 1, NoneMode::class);

        // Unofficial CPU instructions:

        // DOP
        $this->add(0x04, NOP::class, 2, ZeroPageMode::class);
        $this->add(0x14, NOP::class, 2, ZeroPageXMode::class);
        $this->add(0x34, NOP::class, 2, ZeroPageXMode::class);
        $this->add(0x44, NOP::class, 2, ZeroPageMode::class);
        $this->add(0x54, NOP::class, 2, ZeroPageXMode::class);
        $this->add(0x64, NOP::class, 2, ZeroPageMode::class);
        $this->add(0x74, NOP::class, 2, ZeroPageXMode::class);
        $this->add(0x80, NOP::class, 2, ImmediateMode::class);
        $this->add(0x82, NOP::class, 2, ImmediateMode::class);
        $this->add(0x89, NOP::class, 2, ImmediateMode::class);
        $this->add(0xC2, NOP::class, 2, ImmediateMode::class);
        $this->add(0xD4, NOP::class, 2, ZeroPageXMode::class);
        $this->add(0xE2, NOP::class, 2, ImmediateMode::class);
        $this->add(0xF4, NOP::class, 2, ZeroPageXMode::class);

        // TOP
        $this->add(0x0C, NOP::class, 3, AbsoluteMode::class);
        $this->add(0x1C, NOP::class, 3, AbsoluteXMode::class);
        $this->add(0x3C, NOP::class, 3, AbsoluteXMode::class);
        $this->add(0x5C, NOP::class, 3, AbsoluteXMode::class);
        $this->add(0x7C, NOP::class, 3, AbsoluteXMode::class);
        $this->add(0xDC, NOP::class, 3, AbsoluteXMode::class);
        $this->add(0xFC, NOP::class, 3, AbsoluteXMode::class);

        $this->add(0x47, SRE::class, 2, ZeroPageMode::class);
        $this->add(0x57, SRE::class, 2, ZeroPageXMode::class);
        $this->add(0x4F, SRE::class, 3, AbsoluteMode::class);
        $this->add(0x5F, SRE::class, 3, AbsoluteXMode::class);
        $this->add(0x5B, SRE::class, 3, AbsoluteYMode::class);
        $this->add(0x43, SRE::class, 2, IndirectXMode::class);
        $this->add(0x53, SRE::class, 2, IndirectYMode::class);

        $this->add(0x1A, NOP::class, 1, NoneMode::class);
        $this->add(0x3A, NOP::class, 1, NoneMode::class);
        $this->add(0x5A, NOP::class, 1, NoneMode::class);
        $this->add(0x7A, NOP::class, 1, NoneMode::class);
        $this->add(0xDA, NOP::class, 1, NoneMode::class);
        $this->add(0xFA, NOP::class, 1, NoneMode::class);

        $this->add(0xA7, LAX::class, 2, ZeroPageMode::class);
        $this->add(0xB7, LAX::class, 2, ZeroPageYMode::class);
        $this->add(0xAF, LAX::class, 3, AbsoluteMode::class);
        $this->add(0xBF, LAX::class, 3, AbsoluteYMode::class);
        $this->add(0xA3, LAX::class, 2, IndirectXMode::class);
        $this->add(0xB3, LAX::class, 2, IndirectYMode::class);

        $this->add(0x02, NOP::class, 1, NoneMode::class);
        $this->add(0x12, NOP::class, 1, NoneMode::class);
        $this->add(0x22, NOP::class, 1, NoneMode::class);
        $this->add(0x32, NOP::class, 1, NoneMode::class);
        $this->add(0x42, NOP::class, 1, NoneMode::class);
        $this->add(0x52, NOP::class, 1, NoneMode::class);
        $this->add(0x62, NOP::class, 1, NoneMode::class);
        $this->add(0x72, NOP::class, 1, NoneMode::class);
        $this->add(0x92, NOP::class, 1, NoneMode::class);
        $this->add(0xB2, NOP::class, 1, NoneMode::class);
        $this->add(0xD2, NOP::class, 1, NoneMode::class);
        $this->add(0xF2, NOP::class, 1, NoneMode::class);

        $this->add(0x87, AAX::class, 2, ZeroPageMode::class);
        $this->add(0x97, AAX::class, 2, ZeroPageYMode::class);
        $this->add(0x83, AAX::class, 2, IndirectXMode::class);
        $this->add(0x8F, AAX::class, 3, AbsoluteMode::class);

        $this->add(0x0B, AAC::class, 2, ImmediateMode::class);
        $this->add(0x2B, AAC::class, 2, ImmediateMode::class);
    }
}
