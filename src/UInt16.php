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
        $newValue = ($this->value + 1) % 65536;

        return new self($newValue);
    }
}
