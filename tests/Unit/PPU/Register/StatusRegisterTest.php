<?php

declare(strict_types=1);

namespace Tests\Unit\PPU\Register;

use App\PPU\Register\StatusRegister;
use PHPUnit\Framework\TestCase;

final class StatusRegisterTest extends TestCase
{
    public function test(): void
    {
        $statusRegister = new StatusRegister(0b00000000);

        $this->assertSame($statusRegister->get(), 0b00000000);

        $statusRegister->setSprite0Flag();
        $statusRegister->setVblankFlag();

        $this->assertSame($statusRegister->get(), 0b11000000);
        $this->assertSame($statusRegister->getVblankFlag(), true);

        $statusRegister->clearVblankFlag();

        $this->assertSame($statusRegister->get(), 0b01000000);
        $this->assertSame($statusRegister->getVblankFlag(), false);
    }
}
