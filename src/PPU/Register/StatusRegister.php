<?php

declare(strict_types=1);

namespace App\PPU\Register;

use App\Util\UInt8;

final class StatusRegister
{
    private const VBLANK_FLAG  = 0b10000000;
    private const SPRITE0_FLAG = 0b01000000;

    /*
     * PPUSTATUS - Rendering events ($2002 read)
     *
     * 7  bit  0
     * ---- ----
     * VSOx xxxx
     * |||| ||||
     * |||+-++++- (PPU open bus or 2C05 PPU identifier)
     * ||+------- Sprite overflow flag
     * |+-------- Sprite 0 hit flag
     * +--------- Vblank flag, cleared on read. Unreliable; see below.
     */
    private int /* UInt8 */ $status = 0;

    public function __construct(int /* UInt8 */ $status = 0)
    {
        $this->status = $status;
    }

    public function get(): int /* UInt8 */
    {
        return $this->status;
    }

    public function setSprite0Flag(): void
    {
        $this->status = UInt8::or($this->status, self::SPRITE0_FLAG);
    }

    public function setVblankFlag(): void
    {
        $this->status = UInt8::or($this->status, self::VBLANK_FLAG);
    }

    public function clearVblankFlag(): void
    {
        $this->status = UInt8::and($this->status, UInt8::not(self::VBLANK_FLAG));
    }

    public function getVblankFlag(): bool
    {
        return (UInt8::and($this->status, self::VBLANK_FLAG) !== 0);
    }
}
