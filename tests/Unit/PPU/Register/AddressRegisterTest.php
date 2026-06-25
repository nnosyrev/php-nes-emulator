<?php

declare(strict_types=1);

namespace Tests\Unit\PPU\Register;

use App\PPU\Register\AddressRegister;
use PHPUnit\Framework\TestCase;

final class AddressRegisterTest extends TestCase
{
    public function testBase(): void
    {
        $addressRegister = new AddressRegister();
        $addressRegister->set(0x06);
        $addressRegister->set(0xFF);

        $this->assertSame($addressRegister->get(), 0x06FF);

        $addressRegister->add(2);

        $this->assertSame($addressRegister->get(), 0x0701);
    }

    public function testMirroring(): void
    {
        $addressRegister = new AddressRegister();
        $addressRegister->set(0x40);
        $addressRegister->set(0x11);

        $this->assertSame($addressRegister->get(), 0x4011 & 0b11111111111111);
    }
}
