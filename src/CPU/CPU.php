<?php

declare(strict_types=1);

namespace App\CPU;

use App\Bus\BusInterface;
use App\CPU\Instruction\InstructionFactoryInterface;
use App\CPU\Interrupter\InterrupterInterface;
use App\CPU\Mode\ModeFactory;
use App\CPU\Opcode\OpcodeCollection;
use App\Event\NMIEvent;
use App\Util\Int8;
use App\Util\UInt16;
use App\Util\UInt8;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class CPU implements EventSubscriberInterface
{
    private const PRG_ROM_START = 0x8000;
    private const STACK_START = 0x0100;
    private const SP_END = 0xFF;

    public const FLAG_C = 0b00000001;
    public const FLAG_Z = 0b00000010;
    public const FLAG_I = 0b00000100;
    public const FLAG_D = 0b00001000;
    public const FLAG_B = 0b00010000;
    public const FLAG_V = 0b01000000;
    public const FLAG_N = 0b10000000;

    private int /* UInt8 */ $registerA = 0;
    private int /* UInt8 */ $registerX = 0;
    private int /* UInt8 */ $registerY = 0;

    private bool $flagC = false;
    private bool $flagZ = false;
    private bool $flagI = false;
    private bool $flagD = false;
    private bool $flagB = false;
    private bool $flagV = false;
    private bool $flagN = false;

    private int /* UInt8 */ $SP = self::SP_END;
    private int /* UInt16 */ $PC = self::PRG_ROM_START;

    private bool $PCHasChanged = false;

    private bool $needNMI = false;

    public function __construct(
        private readonly BusInterface $bus,
        private readonly OpcodeCollection $opcodeCollection,
        private readonly InstructionFactoryInterface $instructionFactory,
        private readonly ModeFactory $modeFactory,
        private readonly InterrupterInterface $cpuInterrupter,
    ) {
        $this->cpuInterrupter->init($this, 'run');
    }

    public function tick(): ?array
    {
        return $this->cpuInterrupter->startTick();
    }

    public function run(): void
    {
        // @phpstan-ignore while.alwaysTrue
        while (true) {
            if ($this->needNMI) {
                $this->doNMI();
                $this->needNMI = false;
            }

            $code = $this->getMemory($this->PC);

            $this->endTick();

            $this->incrementPC();

            $opcode = $this->opcodeCollection->get($code);

            $instruction = $this->instructionFactory->make($opcode->instructionClass);
            $mode = $this->modeFactory->make($opcode->modeClass);

            $this->PCHasChanged = false;

            $instruction->execute($this, $mode);

            if (!$this->PCHasChanged) {
                $this->addToPC($opcode->bytes - 1);
            }

            $this->endTick();
        }
    }

    public function setSP(int /* UInt8 */ $new): void
    {
        assert(UInt8::check($new));

        $this->SP = $new;
    }

    public function getSP(): int /* UInt8 */
    {
        return $this->SP;
    }

    public function getPC(): int /* UInt16 */
    {
        return $this->PC;
    }

    public function setPC(int /* UInt16 */ $new): self
    {
        assert(UInt16::check($new));

        $this->PC = $new;

        $this->PCHasChanged = true;

        return $this;
    }

    public function incrementPC(): self
    {
        $this->PC = UInt16::increment($this->PC);

        $this->PCHasChanged = true;

        return $this;
    }

    public function addToPC(int /* UInt8|Int8 */ $add): void
    {
        assert(UInt8::check($add) || Int8::check($add));

        $this->PC = UInt16::add($this->PC, $add);

        $this->PCHasChanged = true;
    }

    public function setRegisterA(int /* UInt8 */ $byte): void
    {
        assert(UInt8::check($byte));

        $this->registerA = $byte;

        $this->setFlagsZNByValue($this->getRegisterA());
    }

    public function getRegisterA(): int /* UInt8 */
    {
        return $this->registerA;
    }

    public function setRegisterX(int /* UInt8 */ $byte): void
    {
        $this->registerX = $byte;

        $this->setFlagsZNByValue($this->getRegisterX());
    }

    public function getRegisterX(): int /* UInt8 */
    {
        return $this->registerX;
    }

    public function setRegisterY(int /* UInt8 */ $byte): void
    {
        assert(UInt8::check($byte));

        $this->registerY = $byte;

        $this->setFlagsZNByValue($this->getRegisterY());
    }

    public function getRegisterY(): int /* UInt8 */
    {
        return $this->registerY;
    }

    public function getFlagC(): bool
    {
        return $this->flagC;
    }

    public function setFlagC(bool $flagC): void
    {
        $this->flagC = $flagC;
    }

    public function getFlagD(): bool
    {
        return $this->flagD;
    }

    public function setFlagD(bool $flagD): void
    {
        $this->flagD = $flagD;
    }

    public function getFlagI(): bool
    {
        return $this->flagI;
    }

    public function setFlagI(bool $flagI): void
    {
        $this->flagI = $flagI;
    }

    public function getFlagV(): bool
    {
        return $this->flagV;
    }

    public function setFlagV(bool $flagV): void
    {
        $this->flagV = $flagV;
    }

    public function getFlagB(): bool
    {
        return $this->flagB;
    }

    public function setFlagB(bool $flagB): void
    {
        $this->flagB = $flagB;
    }

    public function setFlagZ(bool $flagZ): void
    {
        $this->flagZ = $flagZ;
    }

    public function setFlagZByValue(int /* UInt8 */ $byte): void
    {
        assert(UInt8::check($byte));

        $this->setFlagZ($byte === 0);
    }

    public function getFlagZ(): bool
    {
        return $this->flagZ;
    }

    public function setFlagN(bool $flagN): void
    {
        $this->flagN = $flagN;
    }

    public function setFlagNByValue(int /* UInt8 */ $byte): void
    {
        assert(UInt8::check($byte));

        $this->setFlagN(($byte & self::FLAG_N) === self::FLAG_N);
    }

    public function getFlagN(): bool
    {
        return $this->flagN;
    }

    public function setFlagsZNByValue(int /* UInt8 */ $value): void
    {
        assert(UInt8::check($value));

        $this->setFlagZByValue($value);
        $this->setFlagNByValue($value);
    }

    public function getFlagsAsUInt8(): int /* UInt8 */
    {
        $all = '';
        $all .= $this->getFlagN() ? '1' : '0';
        $all .= $this->getFlagV() ? '1' : '0';
        $all .= '1';
        $all .= $this->getFlagB() ? '1' : '0';
        $all .= $this->getFlagD() ? '1' : '0';
        $all .= $this->getFlagI() ? '1' : '0';
        $all .= $this->getFlagZ() ? '1' : '0';
        $all .= $this->getFlagC() ? '1' : '0';

        return bindec($all);
    }

    public function setFlagsFromUInt8(int /* UInt8 */ $value, array $without = []): void
    {
        assert(UInt8::check($value));

        $withoutKeys = array_fill_keys($without, true);

        if (!isset($withoutKeys[self::FLAG_N])) {
            $this->setFlagN(($value & self::FLAG_N) === self::FLAG_N);
        }

        if (!isset($withoutKeys[self::FLAG_V])) {
            $this->setFlagV(($value & self::FLAG_V) === self::FLAG_V);
        }

        if (!isset($withoutKeys[self::FLAG_B])) {
            $this->setFlagB(($value & self::FLAG_B) === self::FLAG_B);
        }

        if (!isset($withoutKeys[self::FLAG_D])) {
            $this->setFlagD(($value & self::FLAG_D) === self::FLAG_D);
        }

        if (!isset($withoutKeys[self::FLAG_I])) {
            $this->setFlagI(($value & self::FLAG_I) === self::FLAG_I);
        }

        if (!isset($withoutKeys[self::FLAG_Z])) {
            $this->setFlagZ(($value & self::FLAG_Z) === self::FLAG_Z);
        }

        if (!isset($withoutKeys[self::FLAG_C])) {
            $this->setFlagC(($value & self::FLAG_C) === self::FLAG_C);
        }
    }

    public function setMemory(int /* UInt16 */ $addr, int /* UInt8 */ $value, bool $dummy = false): void
    {
        $this->bus->setMemory($addr, $value, $dummy);
    }

    public function getMemory(int /* UInt16 */ $addr, bool $dummy = false): int /* UInt8 */
    {
        return $this->bus->getMemory($addr, $dummy);
    }

    public function setMemoryUInt16(int /* UInt16 */ $addr, int /* UInt16 */ $data, bool $dummy = false): void
    {
        assert(UInt16::check($addr));
        assert(UInt16::check($data));

        $this->bus->setMemoryUInt16($addr, $data, $dummy);
    }

    public function getMemoryUInt16(int /* UInt16 */ $addr, bool $dummy = false): int /* UInt16 */
    {
        assert(UInt16::check($addr));

        return $this->bus->getMemoryUInt16($addr, $dummy);
    }

    public function pushToStack(int /* UInt8 */ $data): void
    {
        assert(UInt8::check($data));

        $addr = UInt16::add(self::STACK_START, $this->SP);

        assert(UInt16::check($addr));

        $this->setMemory($addr, $data);

        $this->SP = UInt8::decrement($this->SP);

        $this->endTick();
    }

    public function popFromStack(): int /* UInt8 */
    {
        // Dummy read
        $this->getMemory(UInt16::add(self::STACK_START, $this->SP), dummy: true);
        $this->endTick();

        $this->SP = UInt8::increment($this->SP);

        $addr = UInt16::add(self::STACK_START, $this->SP);

        assert(UInt16::check($addr));

        return $this->getMemory($addr);
    }

    public function pushToStackUInt16(int /* UInt16 */ $value): void
    {
        $high = $value >> 8;
        $low = $value & 0xFF;

        $this->pushToStack($high);
        $this->pushToStack($low);
    }

    public function popFromStackUInt16(): int /* UInt16 */
    {
        $low = $this->popFromStack();
        $high = $this->popFromStack();

        $result = ($high << 8) | $low;

        assert(UInt16::check($result));

        return $result;
    }

    public function onNMI(NMIEvent $event): void
    {
        $this->needNMI = true;
    }

    private function doNMI(): void
    {
        $this->pushToStackUInt16($this->getPC());

        // Clearing B flag
        $flags = $this->getFlagsAsUInt8();
        $flags = UInt8::and($flags, ~ self::FLAG_B);

        $this->pushToStack($flags);

        $this->setFlagI(true);

        $this->setPC($this->getMemoryUInt16(0xFFFA));
    }

    public function endTick(): void
    {
        $this->cpuInterrupter->endTick();
    }

    public static function getSubscribedEvents(): array
    {
        return [
            NMIEvent::class => 'onNMI',
        ];
    }
}
