<?php

declare(strict_types=1);

namespace App\PPU;

use App\Rom\RomInterface;

final class Renderer
{
    private array $pallete;

    private Frame $frame;

    public function __construct(
        private readonly RomInterface $rom,
        private readonly PPU $ppu,
    ) {
        $this->pallete = [
            0x808080,
            0xFF0000,
            0x008F00,
            10000000,
        ];

        $this->frame = new Frame(0x000000);
    }

    public function render(): Frame
    {
        $ppu = $this->ppu;

        $chrRom = $this->rom->getChrRom();
        $frame = $this->frame;

        // Render background
        $backgroundBankStart = $ppu->getBackgroundChrBank() * 0x1000;

        $vram = $ppu->getVRam();
        for ($i = 0x2000; $i <= 0x23BF; $i++) {
            $offset = $i - 0x2000;
            $tileN = $vram[$offset];

            // TODO: !!!
            $tile = \array_slice($chrRom, $backgroundBankStart + $tileN * 16, 16);

            $baseX = ($offset % 32) * 8;
            $baseY = (int) \floor($offset / 32) * 8;

            for ($y = 0; $y <= 7; $y++) {
                $upper = $tile[$y];
                $lower = $tile[$y + 8];

                for ($x = 7; $x >= 0; $x--) {
                    $value = (1 & $upper) << 1 | (1 & $lower);
                    $upper = $upper >> 1;
                    $lower = $lower >> 1;

                    $frame->setPixel($baseX + $x, $baseY + $y, $this->pallete[$value]);
                }
            }
        }

        // Render sprites
        $oamData = $ppu->getAllOamData();

        $spriteBankStart = $ppu->getSpriteChrBank() * 0x1000;

        for ($i = 252; $i >= 0; $i = $i - 4) {
            $baseY = $oamData[$i];
            $tileN = $oamData[$i + 1];
            //$attributes = $oamData[$i + 2];
            $baseX = $oamData[$i + 3];

            // TODO: !!!
            $tile = \array_slice($chrRom, $spriteBankStart + $tileN * 16, 16);

            for ($y = 0; $y <= 7; $y++) {
                $upper = $tile[$y];
                $lower = $tile[$y + 8];

                for ($x = 7; $x >= 0; $x--) {
                    $value = (1 & $upper) << 1 | (1 & $lower);
                    $upper = $upper >> 1;
                    $lower = $lower >> 1;

                    $frame->setPixel($baseX + $x, $baseY + $y, $this->pallete[$value]);
                }
            }
        }

        return $frame;
    }
}
