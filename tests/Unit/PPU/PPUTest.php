<?php

declare(strict_types=1);

namespace Tests\Unit\PPU;

use App\PPU\PPU;
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

    public function testOamData(): void
    {
        $ppu = $this->getPpu();

        $ppu->setOamAddr(100);
        for ($i = 0; $i <= 255; $i++) {
            $ppu->setOamData($i);
        }

        $this->assertSame($ppu->getOamData(), 0);

        $oamData = $ppu->getAllOamData();

        for ($i = 0, $j = 100; $i <= 255; $i++, $j = ($j + 1) % 256) {
            $this->assertSame($oamData[$j], $i);
        }
    }

    public function testControl(): void
    {
        $ppu = $this->getPpu();

        $ppu->setControl(0b00000000);

        $this->assertSame($ppu->getSpriteChrBank(), false);
        $this->assertSame($ppu->getBackgroundChrBank(), false);

        $ppu->setControl(0b00011000);

        $this->assertSame($ppu->getSpriteChrBank(), true);
        $this->assertSame($ppu->getBackgroundChrBank(), true);
    }

    public function testTick(): void
    {
        $ppu = $this->getPpu();

        $this->assertSame($ppu->getNeedRender(), false);

        for ($i = 0; $i < 341 * 261; $i++) {
            $ppu->tick();
        }

        $this->assertSame($ppu->getNeedRender(), true);

        $ppu->setNeedRenderToFalse();

        $this->assertSame($ppu->getNeedRender(), false);
    }
}
