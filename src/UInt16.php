<?php

declare(strict_types=1);

namespace App;

use Exception;

final class UInt16
{
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

    public function add(UInt8 $add): self
    {
        $newValue = $this->mod($this->value + $add->value);

        return new self($newValue);
    }

    private function mod(int $value): int
    {
        return $value % 65536;
    }
}
