<?php

declare(strict_types=1);

namespace App;

use Exception;

final class UInt8
{
    private const BASE = 256;

    public function __construct(public readonly int $value)
    {
        self::validate($value);
    }

    public static function validate(int $value): void
    {
        if ($value < 0 || $value > 255) {
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

    public function add(UInt8 $add): self
    {
        $newValue = $this->mod($this->value + $add->value);

        return new self($newValue);
    }

    public function and(UInt8 $and): self
    {
        $newValue = $this->value & $and->value;

        return new self($newValue);
    }

    public function or(UInt8 $or): self
    {
        $newValue = $this->value | $or->value;

        return new self($newValue);
    }

    public function xor(UInt8 $xor): self
    {
        $newValue = $this->value ^ $xor->value;

        return new self($newValue);
    }

    public function toUInt16(): UInt16
    {
        return new UInt16($this->value);
    }

    public function toInt8(): Int8
    {
        return Int8::createFromUInt8($this);
    }

    private function mod(int $value): int
    {
        return $value % self::BASE;
    }
}
