<?php

declare(strict_types=1);

namespace App\PPU;

use App\Rom\RomInterface;

final class Renderer
{
    private Frame $frame;

    public function __construct(
        private readonly RomInterface $rom,
        private readonly PPU $ppu,
    ) {
        $this->frame = new Frame(0x000000);
    }

    public function render(): Frame
    {
        $ppu = $this->ppu;

        $chrRom = $this->rom->getChrRom();
        $frame = $this->frame;

        // Render background
        $backgroundBankStart = (int) $ppu->getBackgroundChrBank() * 0x1000;

        $vram = $ppu->getVRam();
        for ($i = 0x2000; $i <= 0x23BF; $i++) {
            $offset = $i - 0x2000;
            $tileN = $vram[$offset];

            // TODO: !!!
            $tile = \array_slice($chrRom, $backgroundBankStart + $tileN * 16, 16);

            $baseX = $offset % 32;
            $baseY = (int) \floor($offset / 32);

            $palette = $this->getBgPalette($baseX, $baseY);

            for ($y = 0; $y <= 7; $y++) {
                $upper = $tile[$y];
                $lower = $tile[$y + 8];

                for ($x = 7; $x >= 0; $x--) {
                    $value = ($lower & 0b00000001) << 1 | ($upper & 0b00000001);
                    $upper = $upper >> 1;
                    $lower = $lower >> 1;

                    $frame->setPixel($baseX * 8 + $x, $baseY * 8 + $y, $palette[$value]);
                }
            }
        }

        // Render sprites
        $oamData = $ppu->getAllOamData();

        $spriteBankStart = (int) $ppu->getSpriteChrBank() * 0x1000;

        for ($i = 252; $i >= 0; $i = $i - 4) {
            $baseY = $oamData[$i];
            $tileN = $oamData[$i + 1];
            $attributes = $oamData[$i + 2];
            $baseX = $oamData[$i + 3];

            $flipVertical   = (($attributes >> 7) & 1) === 1;
            $flipHorizontal = (($attributes >> 6) & 1) === 1;

            // TODO: !!!
            $tile = \array_slice($chrRom, $spriteBankStart + $tileN * 16, 16);

            $palette = $this->getSpritePalette($attributes & 0b11);

            for ($y = 0; $y <= 7; $y++) {
                $upper = $tile[$y];
                $lower = $tile[$y + 8];

                for ($x = 7; $x >= 0; $x--) {
                    $value = ($lower & 0b00000001) << 1 | ($upper & 0b00000001);
                    $upper = $upper >> 1;
                    $lower = $lower >> 1;

                    if ($value !== 0) {
                        match ([$flipHorizontal, $flipVertical]) {
                            [false, false] => $frame->setPixel($baseX + $x, $baseY + $y, $palette[$value]),
                            [true, false] => $frame->setPixel($baseX + 7 - $x, $baseY + $y, $palette[$value]),
                            [false, true] => $frame->setPixel($baseX + $x, $baseY + 7 - $y, $palette[$value]),
                            [true, true] => $frame->setPixel($baseX + 7 - $x, $baseY + 7 - $y, $palette[$value]),
                        };
                    }
                }
            }
        }

        return $frame;
    }

    private function getBgPalette(int $baseX, int $baseY): array
    {
        $attrIndex = (int) ceil($baseY / 4) * 8 + (int) ceil($baseX / 4);

        $attrByte = $this->ppu->getAttributeTable($attrIndex);

        $palette = match ([(int) \floor(($baseX % 4) / 2), (int) \floor(($baseY % 4) / 2)]) {
            [0, 0] => $attrByte & 0b11,
            [1, 0] => ($attrByte >> 2) & 0b11,
            [0, 1] => ($attrByte >> 4) & 0b11,
            [1, 1] => ($attrByte >> 6) & 0b11,
        };

        $start = 1 + $palette * 4;

        return [
            $this->ppu->getPaletteTable(0),
            $this->ppu->getPaletteTable($start),
            $this->ppu->getPaletteTable($start + 1),
            $this->ppu->getPaletteTable($start + 2),
        ];
    }

    private function getSpritePalette(int $palette): array
    {
        $start = 17 + $palette * 4;

        return [
            0,
            $this->ppu->getPaletteTable($start),
            $this->ppu->getPaletteTable($start + 1),
            $this->ppu->getPaletteTable($start + 2),
        ];
    }
}
