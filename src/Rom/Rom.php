<?php

declare(strict_types=1);

namespace App\Rom;

use App\Mirroring;
use App\Util\UInt8;
use Exception;

final class Rom implements RomInterface
{
    private const NES_TAG = [0x4E, 0x45, 0x53, 0x1A];
    private const PRG_ROM_PAGE_SIZE = 16384;
    private const CHR_ROM_PAGE_SIZE = 8192;

    private Mirroring $mirroring;

    private array $prgRom;

    private array $chrRom;

    private int /* UInt8 */ $mapper;

    public function __construct(private readonly string $file)
    {
        $this->parse();
    }

    public function getPrgRom(): array
    {
        return $this->prgRom;
    }

    public function getChrRom(): array
    {
        return $this->chrRom;
    }

    public function getMapper(): int /* UInt8 */
    {
        return $this->mapper;
    }

    public function getMirroring(): Mirroring
    {
        return $this->mirroring;
    }

    private function parse(): void
    {
        if (!$this->checkTag()) {
            throw new Exception('Incorrect file format.');
        }

        //$iNesVersion = ($this->getNByte(7) >> 2) & 0b11;
        $iNesVersion = UInt8::and(UInt8::shiftToRight($this->getNByte(7), 2), 0b11);
        if ($iNesVersion !== 0) {
            throw new Exception('NES2.0 format is not supported.');
        }

        $fourScreen = ($this->getNByte(6) & 0b1000) !== 0;
        $verticalMirroring = ($this->getNByte(6) & 0b1) !== 0;

        if ($fourScreen) {
            $this->mirroring = Mirroring::FourScreen;
        } elseif ($verticalMirroring) {
            $this->mirroring = Mirroring::Vertical;
        } else {
            $this->mirroring = Mirroring::Horizontal;
        }

        $prgRomSize = $this->getNByte(4) * self::PRG_ROM_PAGE_SIZE;
        $chrRomSize = $this->getNByte(5) * self::CHR_ROM_PAGE_SIZE;

        $skipTrainer = ($this->getNByte(6) & 0b100) !== 0;

        $prgRomStart = 16 + ($skipTrainer ? 512 : 0);
        $chrRomStart = $prgRomStart + $prgRomSize;

        $this->prgRom = $this->getBytes($prgRomStart, $prgRomSize);
        $this->chrRom = $this->getBytes($chrRomStart, $chrRomSize);

        $this->mapper = ($this->getNByte(7) & 0b11110000) | ($this->getNByte(6) >> 4);
    }

    private function checkTag(): bool
    {
        foreach (self::NES_TAG as $key => $value) {
            if ($this->getNByte($key) !== $value) {
                return false;
            }
        }

        return true;
    }

    private function getBytes(int $from, int $size): array
    {
        $result = [];
        for ($i = $from; $i < $from + $size; $i++) {
            $result[] = $this->getNByte($i);
        }

        return $result;
    }

    private function getNByte(int $n): int /* UInt8 */
    {
        if (!isset($this->file[$n])) {
            throw new Exception('Invalid byte address');
        }

        $byte = \ord($this->file[$n]);

        return $byte;
    }
}
