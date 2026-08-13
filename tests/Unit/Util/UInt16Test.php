<?php

declare(strict_types=1);

namespace Tests\Unit\CPU;

use App\Util\UInt16;
use PHPUnit\Framework\TestCase;

final class UInt16Test extends TestCase
{
    public function testCheck(): void
    {
        $this->assertSame(UInt16::check(100), true);
        $this->assertSame(UInt16::check(0), true);
        $this->assertSame(UInt16::check(65535), true);
        $this->assertSame(UInt16::check(65537), false);
        $this->assertSame(UInt16::check(-1), false);
    }

    public function testAdd(): void
    {
        $this->assertSame(UInt16::add(34, 72), 106);
        $this->assertSame(UInt16::add(65535, 7), 6);
    }

    public function testSubtract(): void
    {
        $this->assertSame(UInt16::subtract(134, 8), 126);
        $this->assertSame(UInt16::subtract(5, 7), 65534);
    }

    public function testIncrement(): void
    {
        $this->assertSame(UInt16::increment(134), 135);
        $this->assertSame(UInt16::increment(65535), 0);
    }

    public function testDecrement(): void
    {
        $this->assertSame(UInt16::decrement(134), 133);
        $this->assertSame(UInt16::decrement(0), 65535);
    }

    public function testShiftToRight(): void
    {
        $this->assertSame(UInt16::shiftToRight(0b00100000, 1), 0b00010000);
        $this->assertSame(UInt16::shiftToRight(0b10000000, 7), 0b00000001);
        $this->assertSame(UInt16::shiftToRight(0b00000100, 3), 0b00000000);
    }

    public function testInInterval(): void
    {
        $this->assertSame(UInt16::inInterval(100, 0, 200), true);
        $this->assertSame(UInt16::inInterval(100, 200, 300), false);
        $this->assertSame(UInt16::inInterval(400, 200, 300), false);
    }

    public function testHexString(): void
    {
        $this->assertSame(UInt16::hexString(65535), '0xFFFF');
        $this->assertSame(UInt16::hexString(43983), '0xABCF');
        $this->assertSame(UInt16::hexString(0), '0x0');
    }
}
