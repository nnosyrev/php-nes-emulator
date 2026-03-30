<?php

declare(strict_types=1);

namespace App\PPU;

use App\Rom\RomInterface;
use App\Type\Rgb;
use App\Type\UInt8;
use App\UI\UIInterface;

final class Renderer
{
    public function __construct(
        private readonly UIInterface $ui,
        private readonly RomInterface $rom,
    ) {}

    public function render(PPU $ppu): void
    {
        $pallete = [
            new Rgb(new UInt8(50), new UInt8(100), new UInt8(200)),
            new Rgb(new UInt8(200), new UInt8(100), new UInt8(50)),
            new Rgb(new UInt8(100), new UInt8(50), new UInt8(200)),
            new Rgb(new UInt8(100), new UInt8(100), new UInt8(100)),
        ];

        $frame = new Frame(new Rgb(new UInt8(255), new UInt8(255), new UInt8(255)));

        // Render background
        $backgroundBankStart = $ppu->getBackgroundChrBank() * 0x1000;

        $vram = $ppu->getVRam();
        for ($i = 0x2000; $i <= 0x23BF; $i++) {
            $offset = $i - 0x2000;
            $tileN = $vram[$offset];

            $tile = array_slice($this->rom->getChrRom(), $backgroundBankStart + $tileN * 16, 16);

            $baseX = ($offset % 32) * 8;
            $baseY = (int) floor($offset / 32) * 8;

            foreach (range(0, 7) as $y) {
                $upper = $tile[$y];
                $lower = $tile[$y + 8];

                foreach (range(7, 0) as $x) {
                    $value = (1 & $upper) << 1 | (1 & $lower);
                    $upper = $upper >> 1;
                    $lower = $lower >> 1;

                    $frame->setPixel($baseX + $x, $baseY + $y, $pallete[$value]);
                }
            }
        }

        // Render sprites
        $oamData = $ppu->getAllOamData();

        $spriteBankStart = $ppu->getSpriteChrBank() * 0x1000;

        for ($i = 252; $i >= 0; $i = $i - 4) {
            $baseY = $oamData[$i];
            $tileN = $oamData[$i + 1];
            $attributes = $oamData[$i + 2];
            $baseX = $oamData[$i + 3];

            $tile = array_slice($this->rom->getChrRom(), $spriteBankStart + $tileN * 16, 16);

            foreach (range(0, 7) as $y) {
                $upper = $tile[$y];
                $lower = $tile[$y + 8];

                foreach (range(7, 0) as $x) {
                    $value = (1 & $upper) << 1 | (1 & $lower);
                    $upper = $upper >> 1;
                    $lower = $lower >> 1;

                    $frame->setPixel($baseX + $x, $baseY + $y, $pallete[$value]);
                }
            }
        }

        $this->ui->render($frame);
    }

    /*
    public function render(PPU $ppu): void
    {
        $pallete = [
            new Rgb(new UInt8(50), new UInt8(100), new UInt8(200)),
            new Rgb(new UInt8(200), new UInt8(100), new UInt8(50)),
            new Rgb(new UInt8(100), new UInt8(50), new UInt8(200)),
            new Rgb(new UInt8(100), new UInt8(100), new UInt8(100)),
        ];

        $frame = $this->getFrame(0, $pallete);

        $this->ui->render($frame);
    }

    private function getFrame(int $bank, array $pallete): Frame
    {
        if ($bank > 1 || $bank < 0) {
            throw new \Exception('Incorrect bank');
        }

        $frame = new Frame(new Rgb(new UInt8(255), new UInt8(255), new UInt8(255)));

        if (count($this->rom->getChrRom()) === 0) {
            // CHR ROM is empty
            return $frame;
        }

        $bankStart = $bank * 0x1000;

        foreach (range(0, 255) as $tileN) {
            $tile = array_slice($this->rom->getChrRom(), $bankStart + $tileN * 16, 16);

            $baseX = ($tileN % 32) * 8;
            $baseY = (int) floor($tileN / 32) * 8;

            foreach (range(0, 7) as $y) {
                $upper = $tile[$y];
                $lower = $tile[$y + 8];

                foreach (range(7, 0) as $x) {
                    $value = (1 & $upper) << 1 | (1 & $lower);
                    $upper = $upper >> 1;
                    $lower = $lower >> 1;

                    $frame->setPixel($baseX + $x, $baseY + $y, $pallete[$value]);
                }
            }
        }

        return $frame;
    }
    */
}
