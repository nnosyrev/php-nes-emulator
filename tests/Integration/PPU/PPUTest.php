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

    public function testMirrorPaletteRam(): void
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
