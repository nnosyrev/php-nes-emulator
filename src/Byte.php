<?php

declare(strict_types=1);

namespace App;

use Exception;

final class Byte
{
    public function __construct(public readonly int $value)
    {
        if ($value < 0 || $value > 255) {
            throw new Exception('Invalid byte value');
        }
    }

    public function increment(): self
    {
        $newValue = ($this->value + 1) % 256;

        return new self($newValue);
    }
}
