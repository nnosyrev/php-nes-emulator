<?php

declare(strict_types=1);

namespace App;

use Exception;

final class UInt8
{
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

    public function add(UInt8 $add): self
    {
        $newValue = $this->mod($this->value + $add->value);

        return new self($newValue);
    }

    public function toUInt16(): UInt16
    {
        return new UInt16($this->value);
    }

    private function mod(int $value): int
    {
        return $value % 256;
    }
}
