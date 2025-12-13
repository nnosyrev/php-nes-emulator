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
        $newValue = ($this->value + 1) % 256;

        return new self($newValue);
    }
}
