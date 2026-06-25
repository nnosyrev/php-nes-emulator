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
        $controlRegister->set(0b00000100);

        $this->assertSame($controlRegister->getAddressIncrement(), 32);

        $controlRegister->set(0b00000000);

        $this->assertSame($controlRegister->getAddressIncrement(), 1);
    }
}
