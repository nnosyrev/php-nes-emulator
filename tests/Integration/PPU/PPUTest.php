<?php

declare(strict_types=1);

namespace Tests\Integration\PPU;

use App\Mirroring;
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

    public function testVramWrites(): void
    {
        $this->container->get(ControlRegister::class)->set(0); // Increment = 1

        $rom = $this->createStub(RomInterface::class);
        $rom->method('getMirroring')
            ->willReturn(Mirroring::Vertical);
        $this->container->set(RomInterface::class, $rom);

        $ppu = $this->getPpu();

        $ppu->setAddress(0x23);
        $ppu->setAddress(0x05);

        $ppu->setData(0x66);

        $this->assertSame($ppu->getVRam()[0x0305], 0x66);
    }

    public function testVramReads(): void
    {
        $this->container->get(ControlRegister::class)->set(0); // Increment = 1

        $rom = $this->createStub(RomInterface::class);
        $rom->method('getMirroring')
            ->willReturn(Mirroring::Vertical);
        $this->container->set(RomInterface::class, $rom);

        $ppu = $this->getPpu();

        $ppu->getVRam()[0x0305] = 0x66;

        $ppu->setAddress(0x23);
        $ppu->setAddress(0x05);

        $ppu->getData(); // load into buffer

        $this->assertSame($ppu->getData(), 0x66);
    }

    public function testVramReadsCrossPage(): void
    {
        $this->container->get(ControlRegister::class)->set(0); // Increment = 1

        $rom = $this->createStub(RomInterface::class);
        $rom->method('getMirroring')
            ->willReturn(Mirroring::Vertical);
        $this->container->set(RomInterface::class, $rom);

        $ppu = $this->getPpu();

        $ppu->getVRam()[0x01FF] = 0x66;
        $ppu->getVRam()[0x0200] = 0x77;

        $ppu->setAddress(0x21);
        $ppu->setAddress(0xFF);

        $ppu->getData(); // load into buffer

        $this->assertSame($ppu->getData(), 0x66);
        $this->assertSame($ppu->getData(), 0x77);
    }

    public function testVramReadsStep32(): void
    {
        $this->container->get(ControlRegister::class)->set(0b100); // Increment = 32

        $rom = $this->createStub(RomInterface::class);
        $rom->method('getMirroring')
            ->willReturn(Mirroring::Vertical);
        $this->container->set(RomInterface::class, $rom);

        $ppu = $this->getPpu();

        $ppu->getVRam()[0x01FF] = 0x66;
        $ppu->getVRam()[0x01FF + 32] = 0x77;
        $ppu->getVRam()[0x01FF + 64] = 0x88;

        $ppu->setAddress(0x21);
        $ppu->setAddress(0xFF);

        $ppu->getData(); // load into buffer

        $this->assertSame($ppu->getData(), 0x66);
        $this->assertSame($ppu->getData(), 0x77);
        $this->assertSame($ppu->getData(), 0x88);
    }

    // [0x2000 A ] [0x2400 a ]
    // [0x2800 B ] [0x2C00 b ]
    public function testVramHorizontalMirror(): void
    {
        $this->container->get(ControlRegister::class)->set(0); // Increment = 1

        $rom = $this->createStub(RomInterface::class);
        $rom->method('getMirroring')
            ->willReturn(Mirroring::Horizontal);
        $this->container->set(RomInterface::class, $rom);

        $ppu = $this->getPpu();

        $ppu->setAddress(0x24);
        $ppu->setAddress(0x05);

        $ppu->setData(0x66); // Write to a

        $ppu->setAddress(0x28);
        $ppu->setAddress(0x05);

        $ppu->setData(0x77); // Write to B

        $ppu->setAddress(0x20);
        $ppu->setAddress(0x05);

        $ppu->getData(); // load into buffer
        $this->assertSame($ppu->getData(), 0x66); // Read from A

        $ppu->setAddress(0x2C);
        $ppu->setAddress(0x05);

        $ppu->getData(); // load into buffer
        $this->assertSame($ppu->getData(), 0x77); // Read from b
    }

    // [0x2000 A ] [0x2400 B ]
    // [0x2800 a ] [0x2C00 b ]
    public function testVramVerticalMirror(): void
    {
        $this->container->get(ControlRegister::class)->set(0); // Increment = 1

        $rom = $this->createStub(RomInterface::class);
        $rom->method('getMirroring')
            ->willReturn(Mirroring::Vertical);
        $this->container->set(RomInterface::class, $rom);

        $ppu = $this->getPpu();

        $ppu->setAddress(0x20);
        $ppu->setAddress(0x05);

        $ppu->setData(0x66); // Write to A

        $ppu->setAddress(0x2C);
        $ppu->setAddress(0x05);

        $ppu->setData(0x77); // Write to b

        $ppu->setAddress(0x28);
        $ppu->setAddress(0x05);

        $ppu->getData(); // load into buffer
        $this->assertSame($ppu->getData(), 0x66); // Read from a

        $ppu->setAddress(0x24);
        $ppu->setAddress(0x05);

        $ppu->getData(); // load into buffer
        $this->assertSame($ppu->getData(), 0x77); // Read from B
    }

    public function testReadStatusResetsLatch(): void
    {
        $this->container->get(ControlRegister::class)->set(0); // Increment = 1

        $rom = $this->createStub(RomInterface::class);
        $rom->method('getMirroring')
            ->willReturn(Mirroring::Vertical);
        $this->container->set(RomInterface::class, $rom);

        $ppu = $this->getPpu();

        $ppu->getVRam()[0x0305] = 0x66;

        $ppu->setAddress(0x21);
        $ppu->setAddress(0x23);
        $ppu->setAddress(0x05);

        $ppu->getData(); // load into buffer
        $this->assertNotSame($ppu->getData(), 0x66);

        $ppu->getStatus();

        $ppu->setAddress(0x23);
        $ppu->setAddress(0x05);

        $ppu->getData(); // load into buffer
        $this->assertSame($ppu->getData(), 0x66);
    }
}
