<?php

declare(strict_types=1);

namespace Tests\Unit\PPU\Register;

use App\PPU\Register\AddressRegister;
use App\Type\UInt8;
use PHPUnit\Framework\TestCase;

final class AddressRegisterTest extends TestCase
{
    public function testBase(): void
    {
        $addressRegister = new AddressRegister();
        $addressRegister->set(new UInt8(0x06));
        $addressRegister->set(new UInt8(0xFF));

        $this->assertSame($addressRegister->get()->value, 0x06FF);

        $addressRegister->add(new UInt8(2));

        $this->assertSame($addressRegister->get()->value, 0x0701);
    }

    public function testMirroring(): void
    {
        $addressRegister = new AddressRegister();
        $addressRegister->set(new UInt8(0x40));
        $addressRegister->set(new UInt8(0x11));

        $this->assertSame($addressRegister->get()->value, 0x4011 & 0b11111111111111);
    }
}
