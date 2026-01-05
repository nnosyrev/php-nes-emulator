<?php

declare(strict_types=1);

namespace App;

use Exception;

final class Int8
{
    public function __construct(public readonly int $value)
    {
        if ($value < -128 || $value > 127) {
            throw new Exception('Invalid value');
        }
    }

    public static function createFromUInt8(UInt8 $uint8): self
    {
        $old = $uint8->value;

        $new = ($old >= 0x80) ? -(($old ^ 0xFF) + 1) : $old;

        return new self($new);
    }
}
