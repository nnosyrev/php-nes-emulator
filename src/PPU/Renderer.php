<?php

declare(strict_types=1);

namespace App\PPU;

use App\Frame;
use App\Rom\RomInterface;
use App\Type\Rgb;
use App\Type\UInt8;
use App\UI\UIInterface;

final class Renderer
{
    public function __construct(
        private readonly UIInterface $ui,
    ) {}

    public function render(RomInterface $rom): void
    {
        $pallete = [
            new Rgb(new UInt8(50), new UInt8(100), new UInt8(200)),
            new Rgb(new UInt8(200), new UInt8(100), new UInt8(50)),
            new Rgb(new UInt8(100), new UInt8(50), new UInt8(200)),
            new Rgb(new UInt8(100), new UInt8(100), new UInt8(100)),
        ];

        $frame = $this->getFrame($rom, 0, $pallete);

        $this->ui->render($frame);
    }

    private function getFrame(RomInterface $rom, int $bank, array $pallete): Frame
    {
        if ($bank > 1 || $bank < 0) {
            throw new \Exception('Incorrect bank');
        }

        $frame = new Frame(new Rgb(new UInt8(255), new UInt8(255), new UInt8(255)));

        if (count($rom->getChrRom()) === 0) {
            // CHR ROM is empty
            return $frame;
        }

        $bankStart = $bank * 0x1000;

        foreach (range(0, 255) as $tileN) {
            $tile = array_slice($rom->getChrRom(), $bankStart + $tileN * 16, 16);

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
}
