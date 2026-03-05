<?php

declare(strict_types=1);

namespace App\PPU;

use App\Mirroring;
use App\Type\UInt16;
use App\Type\UInt8;
use Exception;
use SplFixedArray;

final class PPU
{
    // TODO: it's may be wrong (32)
    private SplFixedArray $palleteTable = new \SplFixedArray(32);

    private SplFixedArray $vram = new \SplFixedArray(2048);

    private SplFixedArray $oamData = new \SplFixedArray(256);

    private UInt8 $dataBuf = new UInt8(0);

    public function __construct(
        private readonly array $chrRom,
        private readonly Mirroring $mirroring,
        private readonly AddressRegister $addressRegister,
        private readonly ControlRegister $controlRegister,
        private readonly MaskRegister $maskRegister,
    ) {}

    /**
     * Writing to Controller (0x2000) register
     */
    public function setControl(UInt8 $value): void
    {
        $this->controlRegister->set($value);
    }

    /**
     * Writing to Mask (0x2001) register
     */
    public function setMask(UInt8 $value): void
    {
        $this->maskRegister->set($value);
    }

    /**
     * Writing to Address (0x2006) register
     */
    public function setAddress(UInt8 $value): void
    {
        $this->addressRegister->set($value);
    }

    /**
     * Reading from Data (0x2007) register
     */
    public function readData(): UInt8
    {
        $addr = $this->addressRegister->get();

        $this->addressRegister->add($this->controlRegister->getAddressIncrement());

        $result = $this->dataBuf;

        if (0x0000 <= $addr <= 0x1FFF) {
            $this->dataBuf = new UInt8($this->chrRom[$addr->value]);
        } elseif (0x2000 <= $addr <= 0x2FFF) {
            $this->dataBuf = new UInt8($this->vram[$this->mirrorVRamAddress($addr)]);
        } elseif (0x3000 <= $addr <= 0x3EFF) {
            throw new Exception('Addres space 0x3000..0x3eff is not expected to be used. Requested: 0x' . dechex($addr->value));
        } elseif (0x3F00 <= $addr <= 0x3FFF) {
            $this->dataBuf = new UInt8($this->palleteTable[$addr->value - 0x3f00]);
        } else {
            throw new Exception('Unexpected access to mirrored space 0x' . dechex($addr->value));
        }

        return $result;
    }

    private function mirrorVRamAddress(UInt16 $addr): UInt16
    {
        $mirroredAddr = $addr->and(new UInt16(0b10111111111111))->value;

        $vramIndex = $mirroredAddr - 0x2000;

        $nameTable = (int) floor($vramIndex / 0x400);

        if ($this->mirroring === Mirroring::Vertical && in_array($nameTable, [2, 3])) {
            $vramIndex = $vramIndex - 0x800;
        } elseif ($this->mirroring === Mirroring::Horizontal && in_array($nameTable, [1, 2])) {
            $vramIndex = $vramIndex - 0x400;
        } elseif ($this->mirroring === Mirroring::Horizontal && $nameTable === 3) {
            $vramIndex = $vramIndex - 0x800;
        }

        return new UInt16($vramIndex);
    }
}
