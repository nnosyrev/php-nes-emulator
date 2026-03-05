<?php

declare(strict_types=1);

namespace Tests\Unit\PPU\Register;

use App\PPU\Register\ControlRegister;
use App\Type\UInt8;
use PHPUnit\Framework\TestCase;

final class ControlRegisterTest extends TestCase
{
    public function test(): void
    {
        $controlRegister = new ControlRegister();
        $controlRegister->set(new UInt8(0b00000100));

        $this->assertSame($controlRegister->getAddressIncrement()->value, 32);

        $controlRegister->set(new UInt8(0b00000000));

        $this->assertSame($controlRegister->getAddressIncrement()->value, 1);
    }
}
