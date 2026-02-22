<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Mirroring;
use App\Rom;
use PHPUnit\Framework\TestCase;

final class RomTest extends TestCase
{
    public function testRom(): void
    {
        $file = file_get_contents(__DIR__ . '/snake.nes');

        $rom = new Rom($file);

        $this->assertSame($rom->getMirroring(), Mirroring::Vertical);
        $this->assertSame(count($rom->getChrRom()), 0);
        $this->assertSame(count($rom->getPrgRom()), 32768);
        $this->assertSame($rom->getMapper()->value, 0);
    }
}
