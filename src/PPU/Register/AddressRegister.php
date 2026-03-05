<?php

declare(strict_types=1);

namespace App\PPU\Register;

use App\Type\UInt16;
use App\Type\UInt8;

final class AddressRegister
{
    private UInt8 $value0;
    private UInt8 $value1;

    private bool $hiPtr = true;

    public function __construct()
    {
        $this->value0 = new UInt8(0);
        $this->value1 = new UInt8(0);
    }

    public function set(UInt8 $data): void
    {
        if ($this->hiPtr) {
            $this->value0 = $data;
        } else {
            $this->value1 = $data;
        }

        $this->mirror();

        $this->hiPtr = !$this->hiPtr;
    }

    private function update(UInt16 $data): void
    {
        $this->value0 = $data->shiftToRight(8)->toUInt8();
        $this->value1 = $data->and(new UInt16(0xFF))->toUInt8();
    }

    public function get(): UInt16
    {
        return $this
            ->value0
            ->toUInt16()
            ->shiftToLeft(8)
            ->or($this->value1->toUInt16());
    }

    public function add(UInt8 $value): void
    {
        $low = $this->value1;

        $this->value1 = $this->value1->add($value);

        if ($low->value > $this->value1->value) {
            $this->value0 = $this->value0->increment();
        }

        $this->mirror();
    }

    private function mirror(): void
    {
        if ($this->get()->value > 0x3FFF) {
            $this->update($this->get()->and(new UInt16(0b11111111111111)));
        }
    }
}
