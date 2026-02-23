<?php

declare(strict_types=1);

namespace App\Type;

use Exception;

final class UInt16
{
    private const BASE = 65536;

    public function __construct(public readonly int $value)
    {
        if ($value < 0 || $value > 65535) {
            throw new Exception('Invalid value');
        }
    }

    public function increment(): self
    {
        $newValue = $this->mod($this->value + 1);

        return new self($newValue);
    }

    public function decrement(): self
    {
        $newValue = $this->mod($this->value - 1 + self::BASE);

        return new self($newValue);
    }

    public function add(UInt8|Int8 $add): self
    {
        $newValue = $this->mod($this->value + $add->value);

        return new self($newValue);
    }

    public function subtract(UInt8 $sub): self
    {
        $newValue = $this->mod($this->value - $sub->value + self::BASE);

        return new self($newValue);
    }

    public function and(UInt16 $and): self
    {
        $newValue = $this->value & $and->value;

        return new self($newValue);
    }

    public function or(UInt16 $or): self
    {
        $newValue = $this->value | $or->value;

        return new self($newValue);
    }

    public function shiftToLeft(int $bits): self
    {
        $newValue = $this->mod($this->value << $bits);

        return new self($newValue);
    }

    public function shiftToRight(int $bits): self
    {
        $newValue = $this->value >> $bits;

        return new self($newValue);
    }

    public function toUInt8(): UInt8
    {
        return UInt8::createFromUInt16($this);
    }

    private function mod(int $value): int
    {
        return $value % self::BASE;
    }
}
