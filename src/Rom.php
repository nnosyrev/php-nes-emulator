<?php

declare(strict_types=1);

namespace App;

use Exception;

final class Rom
{
    private const NES_TAG = [0x4E, 0x45, 0x53, 0x1A];
    private const PRG_ROM_PAGE_SIZE = 16384;
    private const CHR_ROM_PAGE_SIZE = 8192;

    private Mirroring $mirroring;

    private array $prgRom;

    private array $chrRom;

    private UInt8 $mapper;

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

    public function getMapper(): UInt8
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

        $iNesVersion = $this->getNByte(7)->shiftToRight(2)->and(new UInt8(0b11));
        if ($iNesVersion->value !== 0) {
            throw new Exception('NES2.0 format is not supported.');
        }

        $fourScreen = $this->getNByte(6)->and(new UInt8(0b1000))->value !== 0;
        $verticalMirroring = $this->getNByte(6)->and(new UInt8(0b1))->value !== 0;

        if ($fourScreen) {
            $this->mirroring = Mirroring::FourScreen;
        } elseif ($verticalMirroring) {
            $this->mirroring = Mirroring::Vertical;
        } else {
            $this->mirroring = Mirroring::Horizontal;
        }

        $prgRomSize = $this->getNByte(4)->value * self::PRG_ROM_PAGE_SIZE;
        $chrRomSize = $this->getNByte(5)->value * self::CHR_ROM_PAGE_SIZE;

        $skipTrainer = ($this->getNByte(6)->value & 0b100) !== 0;

        $prgRomStart = 16 + ($skipTrainer ? 512 : 0);
        $chrRomStart = $prgRomStart + $prgRomSize;

        $this->prgRom = $this->getBytes($prgRomStart, $prgRomSize);
        $this->chrRom = $this->getBytes($chrRomStart, $chrRomSize);

        $this->mapper = $this->getNByte(7)->and(new UInt8(0b11110000))->or($this->getNByte(6)->shiftToRight(4));
    }

    private function checkTag(): bool
    {
        foreach (self::NES_TAG as $key => $value) {
            if ($this->getNByte($key)->value !== $value) {
                return false;
            }
        }

        return true;
    }

    private function getBytes(int $from, int $size): array
    {
        $result = [];
        for ($i = $from; $i < $from + $size; $i++) {
            $result[] = $this->getNByte($i)->value;
        }

        return $result;
    }

    private function getNByte(int $n): UInt8
    {
        if (!isset($this->file[$n])) {
            throw new Exception('Invalid byte address');
        }

        $byte = ord($this->file[$n]);

        return new UInt8($byte);
    }
}
