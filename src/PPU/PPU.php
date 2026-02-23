<?php

declare(strict_types=1);

namespace App\PPU;

use App\Mirroring;
use App\UInt16;
use App\UInt8;
use Exception;
use SplFixedArray;

final class PPU
{
    private SplFixedArray $palleteTable = new \SplFixedArray(32);

    private SplFixedArray $ram = new \SplFixedArray(2048);

    private SplFixedArray $oamData = new \SplFixedArray(256);

    private UInt8 $dataBuf = new UInt8(0);

    public function __construct(
        private readonly array $chrRom,
        private readonly Mirroring $mirroring,
        private readonly AddressRegister $addressRegister,
        private readonly ControlRegister $controlRegister,
    ) {}

    public function writeToAddressRegister(UInt8 $value): void
    {
        $this->addressRegister->update($value);
    }

    public function writeToControl(UInt8 $value): void
    {
        $this->controlRegister->update($value);
    }

    public function readMemory(): UInt8
    {
        $addr = $this->addressRegister->get();

        $this->addressRegister->add($this->controlRegister->getAddressIncrement());

        $result = $this->dataBuf;

        if (0x0000 <= $addr <= 0x1FFF) {
            $this->dataBuf = new UInt8($this->chrRom[$addr->value]);
        } elseif (0x2000 <= $addr <= 0x2FFF) {
            $this->dataBuf = new UInt8($this->ram[$this->mirrorRamAddress($addr)]);
        } elseif (0x3000 <= $addr <= 0x3EFF) {
            throw new Exception('Addres space 0x3000..0x3eff is not expected to be used. Requested: 0x' . dechex($addr->value));
        } elseif (0x3F00 <= $addr <= 0x3FFF) {
            $this->dataBuf = new UInt8($this->palleteTable[$addr->value - 0x3f00]);
        } else {
            throw new Exception('Unexpected access to mirrored space 0x' . dechex($addr->value));
        }

        return $result;
    }

    private function mirrorRamAddress(UInt16 $addr): UInt16
    {
        // TODO:!!!
        return $addr;
    }
}
