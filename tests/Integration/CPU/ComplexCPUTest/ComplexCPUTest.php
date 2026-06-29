<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\ComplexCPUTest;

use App\Bus\BusInterface;
use App\CPU\CPU;
use App\UI\UIInterface;
use DI\Container;
use DI\ContainerBuilder;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class ComplexCPUTest extends TestCase
{
    private Container $container;

/*
{
	"name": "b1 71 8b",
	"initial": {
		"pc": 9023,
		"s": 240,
		"a": 47,
		"x": 162,
		"y": 170,
		"p": 170,
		"ram": [
			[9023, 177],
			[9024, 113],
			[9025, 139],
			[113, 169],
			[114, 89],
			[22867, 214],
			[23123, 37]
		]
	},
	"final": {
		"pc": 9025,
		"s": 240,
		"a": 37,
		"x": 162,
		"y": 170,
		"p": 40,
		"ram": [
			[113, 169],
			[114, 89],
			[9023, 177],
			[9024, 113],
			[9025, 139],
			[22867, 214],
			[23123, 37]
		]
	},
	"cycles": [
		[9023, 177, "read"],
		[9024, 113, "read"],
		[113, 169, "read"],
		[114, 89, "read"],
		[22867, 214, "read"],
		[23123, 37, "read"]
	]
}'
*/

    protected function setUp(): void
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions(__DIR__ . '/../../../../config/di.php');

        $this->container = $builder->build();
        $this->container->set(UIInterface::class, $this->createStub(UIInterface::class));
        $this->container->set(BusInterface::class, $this->container->get(Bus::class));
    }

    private function getCpu(): CPU
    {
        return $this->container->get(CPU::class);
    }

    #[DataProvider('getData')]
    public function test(array $testData): void
    {
        $cpu = $this->getCpu();

        $cpu->setPC($testData['initial']['pc']);
        $cpu->setRegisterA($testData['initial']['a']);
        $cpu->setRegisterX($testData['initial']['x']);
        $cpu->setRegisterY($testData['initial']['y']);
        $cpu->setSP($testData['initial']['s']);
        $cpu->setFlagsFromUInt8($testData['initial']['p']);

        foreach ($testData['initial']['ram'] as $value) {
            $cpu->setMemory($value[0], $value[1]);
        }

        foreach ($testData['cycles'] as $value) {
            $result = $cpu->tick();
            //var_dump($result);
        }

        $this->assertSame($cpu->getPC(), $testData['final']['pc']);
        $this->assertSame($cpu->getRegisterA(), $testData['final']['a']);
        $this->assertSame($cpu->getRegisterX(), $testData['final']['x']);
        $this->assertSame($cpu->getRegisterY(), $testData['final']['y']);
        $this->assertSame($cpu->getSP(), $testData['final']['s']);
        $this->assertSame($cpu->getFlagsAsUInt8(), $testData['final']['p']);

        foreach ($testData['final']['ram'] as $value) {
            $this->assertSame($cpu->getMemory($value[0]), $value[1]);
        }
    }

    public static function getData(): array
    {
        $dir = __DIR__ . '/TestScenario';

        $allJson = file_get_contents($dir . '/bd.json');

        $allData = json_decode($allJson, true);

        $result = [];
        foreach ($allData as $testData) {
            $result[$testData['name']] = [$testData];
        }

        return $result;
    }
}
