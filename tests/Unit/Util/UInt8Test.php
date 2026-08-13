<?php

declare(strict_types=1);

namespace Tests\Unit\CPU;

use App\Util\UInt8;
use PHPUnit\Framework\TestCase;

final class UInt8Test extends TestCase
{
    public function testCheck(): void
    {
        $this->assertSame(UInt8::check(100), true);
        $this->assertSame(UInt8::check(0), true);
        $this->assertSame(UInt8::check(255), true);
        $this->assertSame(UInt8::check(256), false);
        $this->assertSame(UInt8::check(-1), false);
    }

    public function testAnd(): void
    {
        $this->assertSame(UInt8::and(0b11111111, 0b00010000), 0b00010000);
    }

    public function testOr(): void
    {
        $this->assertSame(UInt8::or(0b11111111, 0b00010000), 0b11111111);
        $this->assertSame(UInt8::or(0b00000000, 0b00010000), 0b00010000);
    }

    public function testAdd(): void
    {
        $this->assertSame(UInt8::add(34, 72), 106);
        $this->assertSame(UInt8::add(252, 7), 3);
    }

    public function testSubtract(): void
    {
        $this->assertSame(UInt8::subtract(134, 8), 126);
        $this->assertSame(UInt8::subtract(5, 7), 254);
    }

    public function testIncrement(): void
    {
        $this->assertSame(UInt8::increment(134), 135);
        $this->assertSame(UInt8::increment(255), 0);
    }

    public function testDecrement(): void
    {
        $this->assertSame(UInt8::decrement(134), 133);
        $this->assertSame(UInt8::decrement(0), 255);
    }

    public function testShiftToLeft(): void
    {
        $this->assertSame(UInt8::shiftToLeft(0b00100000, 1), 0b01000000);
        $this->assertSame(UInt8::shiftToLeft(0b00000001, 7), 0b10000000);
        $this->assertSame(UInt8::shiftToLeft(0b00100000, 3), 0b00000000);
    }

    public function testShiftToRight(): void
    {
        $this->assertSame(UInt8::shiftToRight(0b00100000, 1), 0b00010000);
        $this->assertSame(UInt8::shiftToRight(0b10000000, 7), 0b00000001);
        $this->assertSame(UInt8::shiftToRight(0b00000100, 3), 0b00000000);
    }

    public function testXor(): void
    {
        $this->assertSame(UInt8::xor(0b10100000, 0b00100001), 0b10000001);
        $this->assertSame(UInt8::xor(0b11111111, 0b00000001), 0b11111110);
    }

    public function testNot(): void
    {
        $this->assertSame(UInt8::not(0b00100001), 0b11011110);
    }
}
