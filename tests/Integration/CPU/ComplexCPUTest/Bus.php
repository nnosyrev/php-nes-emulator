<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\ComplexCPUTest;

use App\Bus\BusInterface;
use App\Util\UInt16;

final class Bus implements BusInterface
{
    private array $memory = [];

    public function getMemory(int /* UInt16 */ $addr, bool $dummy = false): int /* UInt8 */
    {
        $result = $this->memory[$addr];

        CycleStorage::push($addr, $result, CycleStorage::TYPE_READ);

        return $result;
    }

    public function setMemory(int /* UInt16 */ $addr, int /* UInt8 */ $data, bool $dummy = false): void
    {
        $this->memory[$addr] = $data;

        CycleStorage::push($addr, $data, CycleStorage::TYPE_WRITE);
    }

    public function setMemoryUInt16(int /* UInt16 */ $addr, int /* UInt16 */ $data, bool $dummy = false): void
    {
        $high = $data >> 8;
        $low = $data & 0xFF;

        $addrIncremented = UInt16::increment($addr);

        $this->memory[$addr] = $low;
        $this->memory[$addrIncremented] = $high;

        CycleStorage::push($addr, $low, CycleStorage::TYPE_WRITE);
        CycleStorage::push($addrIncremented, $high, CycleStorage::TYPE_WRITE);
    }

    public function getMemoryUInt16(int /* UInt16 */ $addr, bool $dummy = false): int /* UInt16 */
    {
        $addrIncremented = UInt16::increment($addr);

        $low = $this->memory[$addr];
        $high = $this->memory[$addrIncremented];

        CycleStorage::push($addr, $low, CycleStorage::TYPE_READ);
        CycleStorage::push($addrIncremented, $high, CycleStorage::TYPE_READ);

        return ($high << 8) | $low;
    }
}
