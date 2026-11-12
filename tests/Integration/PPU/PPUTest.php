<?php

declare(strict_types=1);

namespace Tests\Integration\PPU;

use App\PPU\PPU;
use App\PPU\Register\ControlRegister;
use App\Rom\RomInterface;
use DI\Container;
use DI\ContainerBuilder;
use PHPUnit\Framework\TestCase;

final class PPUTest extends TestCase
{
    private Container $container;

    protected function setUp(): void
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions(__DIR__ . '/../../../config/di.php');

        $this->container = $builder->build();
        $this->container->set(RomInterface::class, $this->createStub(RomInterface::class));
    }

    protected function getPpu(): PPU
    {
        return $this->container->get(PPU::class);
    }

    public function testPaletteRam(): void
    {
        $ppu = $this->getPpu();
        $this->container->get(ControlRegister::class)->set(0); // Increment = 1

        $ppu->setAddress(0x3F);
        $ppu->setAddress(0x01);

        $ppu->setData(0xAF);

        $ppu->setAddress(0x3F);
        $ppu->setAddress(0x01);

        $this->assertSame($ppu->getData(), 0xAF);
    }

    public function testMirror4PaletteRam(): void
    {
        $this->container->get(ControlRegister::class)->set(0); // Increment = 1

        $ppu = $this->getPpu();

        // 0x10
        $ppu->setAddress(0x3F);
        $ppu->setAddress(0x10);

        $ppu->setData(0xA1);

        $ppu->setAddress(0x3F);
        $ppu->setAddress(0x00);

        $this->assertSame($ppu->getData(), 0xA1);

        // 0x14
        $ppu->setAddress(0x3F);
        $ppu->setAddress(0x14);

        $ppu->setData(0xA2);

        $ppu->setAddress(0x3F);
        $ppu->setAddress(0x04);

        $this->assertSame($ppu->getData(), 0xA2);

        // 0x18
        $ppu->setAddress(0x3F);
        $ppu->setAddress(0x18);

        $ppu->setData(0xA3);

        $ppu->setAddress(0x3F);
        $ppu->setAddress(0x08);

        $this->assertSame($ppu->getData(), 0xA3);

        // 0x1C
        $ppu->setAddress(0x3F);
        $ppu->setAddress(0x1C);

        $ppu->setData(0xA4);

        $ppu->setAddress(0x3F);
        $ppu->setAddress(0x0C);

        $this->assertSame($ppu->getData(), 0xA4);
    }

    public function testMirror32PaletteRam(): void
    {
        $ppu = $this->getPpu();
        $this->container->get(ControlRegister::class)->set(0); // Increment = 1

        $ppu->setAddress(0x3F);
        $ppu->setAddress(0x21);

        $ppu->setData(0xAF);

        $ppu->setAddress(0x3F);
        $ppu->setAddress(0x21);

        $this->assertSame($ppu->getData(), 0xAF);

        $ppu->setAddress(0x3F);
        $ppu->setAddress(0x01);

        $this->assertSame($ppu->getData(), 0xAF);

        $ppu->setAddress(0x3F);
        $ppu->setAddress(0x41);

        $this->assertSame($ppu->getData(), 0xAF);
    }
}
