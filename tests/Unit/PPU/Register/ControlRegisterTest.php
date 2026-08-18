<?php

declare(strict_types=1);

namespace Tests\Unit\PPU\Register;

use App\PPU\Register\ControlRegister;
use PHPUnit\Framework\TestCase;

final class ControlRegisterTest extends TestCase
{
    public function test(): void
    {
        $controlRegister = new ControlRegister();
        $controlRegister->set(0b10011100);

        $this->assertSame($controlRegister->getAddressIncrement(), 32);
        $this->assertSame($controlRegister->getNMIEnableBit(), true);
        $this->assertSame($controlRegister->getSpritePatternTableBit(), true);
        $this->assertSame($controlRegister->getBackgroundPatternTableBit(), true);

        $controlRegister->set(0b00000000);

        $this->assertSame($controlRegister->getAddressIncrement(), 1);
        $this->assertSame($controlRegister->getNMIEnableBit(), false);
        $this->assertSame($controlRegister->getSpritePatternTableBit(), false);
        $this->assertSame($controlRegister->getBackgroundPatternTableBit(), false);
    }
}
