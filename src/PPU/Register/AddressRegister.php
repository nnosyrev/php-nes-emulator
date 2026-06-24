<?php

declare(strict_types=1);

namespace App\PPU\Register;

use App\Util\UInt8;

final class AddressRegister
{
    private int /* UInt8 */ $value0 = 0;
    private int /* UInt8 */ $value1 = 0;

    private bool $hiPtr = true;

    public function set(int /* UInt8 */ $data): void
    {
        if ($this->hiPtr) {
            $this->value0 = $data;
        } else {
            $this->value1 = $data;
        }

        $this->mirror();

        $this->hiPtr = !$this->hiPtr;
    }

    private function update(int /* UInt16 */ $data): void
    {
        $this->value0 = $data >> 8;
        $this->value1 = $data & 0xFF;
    }

    public function get(): int /* UInt16 */
    {
        return ($this->value0 << 8) | $this->value1;
    }

    public function add(int /* UInt8 */ $value): void
    {
        $low = $this->value1;

        $this->value1 = UInt8::add($this->value1, $value);

        if ($low > $this->value1) {
            $this->value0 = UInt8::increment($this->value0);
        }

        $this->mirror();
    }

    public function resetLatch(): void
    {
        $this->hiPtr = true;
    }

    private function mirror(): void
    {
        if ($this->get() > 0x3FFF) {
            $this->update($this->get() & 0b11111111111111);
        }
    }
}
