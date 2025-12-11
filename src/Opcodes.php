<?php

declare(strict_types=1);

namespace App;

final class Opcodes
{
    public const LDA = 0xA9;
    public const LDX = 0xA2;
    public const TAX = 0xAA;
    public const INX = 0xE8;
    public const BRK = 0x00;
}
