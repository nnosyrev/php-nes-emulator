<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\ComplexCPUTest;

use App\Bus\BusInterface;

final class Bus implements BusInterface
{
    private array $memory = [];

    public function getMemory(int /* UInt16 */ $addr): int /* UInt8 */
    {
        return $this->memory[$addr];
    }

    public function setMemory(int /* UInt16 */ $addr, int /* UInt8 */ $data): void
    {
        $this->memory[$addr] = $data;
    }

    public function setMemoryUInt16(int /* UInt16 */ $addr, int /* UInt16 */ $data): void
    {
        $high = $data >> 8;
        $low = $data & 0xFF;

        $this->memory[$addr] = $low;
        $this->memory[$addr + 1] = $high;
    }

    public function getMemoryUInt16(int /* UInt16 */ $addr): int /* UInt16 */
    {
        $low = $this->memory[$addr];
        $high = $this->memory[$addr + 1];

        return ($high << 8) | $low;
    }
}
