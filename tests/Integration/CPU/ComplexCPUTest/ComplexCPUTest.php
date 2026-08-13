<?php

declare(strict_types=1);

namespace Tests\Integration\CPU\ComplexCPUTest;

use App\Bus\BusInterface;
use App\CPU\Interrupter\InterrupterInterface;
use App\UI\UIInterface;
use DI\Container;
use DI\ContainerBuilder;
use JsonMachine\Items;
use PHPUnit\Framework\TestCase;
use Tests\CPUWrapper;

final class ComplexCPUTest extends TestCase
{
    private const DIR = __DIR__ . '/TestScenario';

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
    }
    */

    protected function setUp(): void
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions(__DIR__ . '/../../../../config/di.php');

        $this->container = $builder->build();
        $this->container->set(UIInterface::class, $this->createStub(UIInterface::class));
        $this->container->set(BusInterface::class, $this->container->get(Bus::class));
        $this->container->set(InterrupterInterface::class, $this->container->get(InterrupterDecorator::class));
    }

    private function getCpu(): CPUWrapper
    {
        return $this->container->get(CPUWrapper::class);
    }

    private function doTest(string $testScenarioFile): void
    {
        $items = Items::fromFile(self::DIR . DIRECTORY_SEPARATOR . $testScenarioFile);

        foreach ($items as $item) {
            $this->setUp();

            $cpu = $this->getCpu();

            $cpu->setPC($item->initial->pc);
            $cpu->setRegisterA($item->initial->a);
            $cpu->setRegisterX($item->initial->x);
            $cpu->setRegisterY($item->initial->y);
            $cpu->setSP($item->initial->s);
            $cpu->setFlagsFromUInt8($item->initial->p);

            foreach ($item->initial->ram as $value) {
                $cpu->setMemory($value[0], $value[1]);
            }

            CycleStorage::reset();

            foreach ($item->cycles as $value) {
                $this->assertSame($value, $cpu->tick());
            }

            $this->assertSame($cpu->getPC(), $item->final->pc);
            $this->assertSame($cpu->getRegisterA(), $item->final->a);
            $this->assertSame($cpu->getRegisterX(), $item->final->x);
            $this->assertSame($cpu->getRegisterY(), $item->final->y);
            $this->assertSame($cpu->getSP(), $item->final->s);
            $this->assertSame($cpu->getFlagsAsUInt8(), $item->final->p);

            foreach ($item->final->ram as $value) {
                $this->assertSame($cpu->getMemory($value[0]), $value[1]);
            }
        }
    }

    public function test_00(): void
    {
        $this->doTest('00.json');
    }

    public function test_01(): void
    {
        $this->doTest('01.json');
    }

    public function test_02(): void
    {
        $this->doTest('02.json');
    }

    public function test_03(): void
    {
        $this->doTest('03.json');
    }

    public function test_04(): void
    {
        $this->doTest('04.json');
    }

    public function test_05(): void
    {
        $this->doTest('05.json');
    }

    public function test_06(): void
    {
        $this->doTest('06.json');
    }

    public function test_07(): void
    {
        $this->doTest('07.json');
    }

    public function test_08(): void
    {
        $this->doTest('08.json');
    }

    public function test_09(): void
    {
        $this->doTest('09.json');
    }

    public function test_0a(): void
    {
        $this->doTest('0a.json');
    }

    public function test_0b(): void
    {
        $this->doTest('0b.json');
    }

    public function test_0c(): void
    {
        $this->doTest('0c.json');
    }

    public function test_0d(): void
    {
        $this->doTest('0d.json');
    }

    public function test_0e(): void
    {
        $this->doTest('0e.json');
    }

    public function test_0f(): void
    {
        $this->doTest('0f.json');
    }

    //

    public function test_10(): void
    {
        $this->doTest('10.json');
    }

    public function test_11(): void
    {
        $this->doTest('11.json');
    }

    public function test_12(): void
    {
        $this->doTest('12.json');
    }

    public function test_13(): void
    {
        $this->doTest('13.json');
    }

    public function test_14(): void
    {
        $this->doTest('14.json');
    }

    public function test_15(): void
    {
        $this->doTest('15.json');
    }

    public function test_16(): void
    {
        $this->doTest('16.json');
    }

    public function test_17(): void
    {
        $this->doTest('17.json');
    }

    public function test_18(): void
    {
        $this->doTest('18.json');
    }

    public function test_19(): void
    {
        $this->doTest('19.json');
    }

    public function test_1a(): void
    {
        $this->doTest('1a.json');
    }

    public function test_1b(): void
    {
        $this->doTest('1b.json');
    }

    public function test_1c(): void
    {
        $this->doTest('1c.json');
    }

    public function test_1d(): void
    {
        $this->doTest('1d.json');
    }

    public function test_1e(): void
    {
        $this->doTest('1e.json');
    }

    public function test_1f(): void
    {
        $this->doTest('1f.json');
    }

    //

    public function test_20(): void
    {
        $this->doTest('20.json');
    }

    public function test_21(): void
    {
        $this->doTest('21.json');
    }

    public function test_22(): void
    {
        $this->doTest('22.json');
    }

    public function test_23(): void
    {
        $this->doTest('23.json');
    }

    public function test_24(): void
    {
        $this->doTest('24.json');
    }

    public function test_25(): void
    {
        $this->doTest('25.json');
    }

    public function test_26(): void
    {
        $this->doTest('26.json');
    }

    public function test_27(): void
    {
        $this->doTest('27.json');
    }

    public function test_28(): void
    {
        $this->doTest('28.json');
    }

    public function test_29(): void
    {
        $this->doTest('29.json');
    }

    public function test_2a(): void
    {
        $this->doTest('2a.json');
    }

    public function test_2b(): void
    {
        $this->doTest('2b.json');
    }

    public function test_2c(): void
    {
        $this->doTest('2c.json');
    }

    public function test_2d(): void
    {
        $this->doTest('2d.json');
    }

    public function test_2e(): void
    {
        $this->doTest('2e.json');
    }

    public function test_2f(): void
    {
        $this->doTest('2f.json');
    }

    //

    public function test_30(): void
    {
        $this->doTest('30.json');
    }

    public function test_31(): void
    {
        $this->doTest('31.json');
    }

    public function test_32(): void
    {
        $this->doTest('32.json');
    }

    public function test_33(): void
    {
        $this->doTest('33.json');
    }

    public function test_34(): void
    {
        $this->doTest('34.json');
    }

    public function test_35(): void
    {
        $this->doTest('35.json');
    }

    public function test_36(): void
    {
        $this->doTest('36.json');
    }

    public function test_37(): void
    {
        $this->doTest('37.json');
    }

    public function test_38(): void
    {
        $this->doTest('38.json');
    }

    public function test_39(): void
    {
        $this->doTest('39.json');
    }

    public function test_3a(): void
    {
        $this->doTest('3a.json');
    }

    public function test_3b(): void
    {
        $this->doTest('3b.json');
    }

    public function test_3c(): void
    {
        $this->doTest('3c.json');
    }

    public function test_3d(): void
    {
        $this->doTest('3d.json');
    }

    public function test_3e(): void
    {
        $this->doTest('3e.json');
    }

    public function test_3f(): void
    {
        $this->doTest('3f.json');
    }

    //

    public function test_40(): void
    {
        $this->doTest('40.json');
    }

    public function test_41(): void
    {
        $this->doTest('41.json');
    }

    public function test_42(): void
    {
        $this->doTest('42.json');
    }

    public function test_43(): void
    {
        $this->doTest('43.json');
    }

    public function test_44(): void
    {
        $this->doTest('44.json');
    }

    public function test_45(): void
    {
        $this->doTest('45.json');
    }

    public function test_46(): void
    {
        $this->doTest('46.json');
    }

    public function test_47(): void
    {
        $this->doTest('47.json');
    }

    public function test_48(): void
    {
        $this->doTest('48.json');
    }

    public function test_49(): void
    {
        $this->doTest('49.json');
    }

    public function test_4a(): void
    {
        $this->doTest('4a.json');
    }

    public function test_4b(): void
    {
        $this->doTest('4b.json');
    }

    public function test_4c(): void
    {
        $this->doTest('4c.json');
    }

    public function test_4d(): void
    {
        $this->doTest('4d.json');
    }

    public function test_4e(): void
    {
        $this->doTest('4e.json');
    }

    public function test_4f(): void
    {
        $this->doTest('4f.json');
    }

    //

    public function test_50(): void
    {
        $this->doTest('50.json');
    }

    public function test_51(): void
    {
        $this->doTest('51.json');
    }

    public function test_52(): void
    {
        $this->doTest('52.json');
    }

    public function test_53(): void
    {
        $this->doTest('53.json');
    }

    public function test_54(): void
    {
        $this->doTest('54.json');
    }

    public function test_55(): void
    {
        $this->doTest('55.json');
    }

    public function test_56(): void
    {
        $this->doTest('56.json');
    }

    public function test_57(): void
    {
        $this->doTest('57.json');
    }

    public function test_58(): void
    {
        $this->doTest('58.json');
    }

    public function test_59(): void
    {
        $this->doTest('59.json');
    }

    public function test_5a(): void
    {
        $this->doTest('5a.json');
    }

    public function test_5b(): void
    {
        $this->doTest('5b.json');
    }

    public function test_5c(): void
    {
        $this->doTest('5c.json');
    }

    public function test_5d(): void
    {
        $this->doTest('5d.json');
    }

    public function test_5e(): void
    {
        $this->doTest('5e.json');
    }

    public function test_5f(): void
    {
        $this->doTest('5f.json');
    }

    //

    public function test_60(): void
    {
        $this->doTest('60.json');
    }

    public function test_61(): void
    {
        $this->doTest('61.json');
    }

    public function test_62(): void
    {
        $this->doTest('62.json');
    }

    public function test_63(): void
    {
        $this->doTest('63.json');
    }

    public function test_64(): void
    {
        $this->doTest('64.json');
    }

    public function test_65(): void
    {
        $this->doTest('65.json');
    }

    public function test_66(): void
    {
        $this->doTest('66.json');
    }

    public function test_67(): void
    {
        $this->doTest('67.json');
    }

    public function test_68(): void
    {
        $this->doTest('68.json');
    }

    public function test_69(): void
    {
        $this->doTest('69.json');
    }

    public function test_6a(): void
    {
        $this->doTest('6a.json');
    }

    public function test_6b(): void
    {
        $this->doTest('6b.json');
    }

    public function test_6c(): void
    {
        $this->doTest('6c.json');
    }

    public function test_6d(): void
    {
        $this->doTest('6d.json');
    }

    public function test_6e(): void
    {
        $this->doTest('6e.json');
    }

    public function test_6f(): void
    {
        $this->doTest('6f.json');
    }

    //

    public function test_70(): void
    {
        $this->doTest('70.json');
    }

    public function test_71(): void
    {
        $this->doTest('71.json');
    }

    public function test_72(): void
    {
        $this->doTest('72.json');
    }

    public function test_73(): void
    {
        $this->doTest('73.json');
    }

    public function test_74(): void
    {
        $this->doTest('74.json');
    }

    public function test_75(): void
    {
        $this->doTest('75.json');
    }

    public function test_76(): void
    {
        $this->doTest('76.json');
    }

    public function test_77(): void
    {
        $this->doTest('77.json');
    }

    public function test_78(): void
    {
        $this->doTest('78.json');
    }

    public function test_79(): void
    {
        $this->doTest('79.json');
    }

    public function test_7a(): void
    {
        $this->doTest('7a.json');
    }

    public function test_7b(): void
    {
        $this->doTest('7b.json');
    }

    public function test_7c(): void
    {
        $this->doTest('7c.json');
    }

    public function test_7d(): void
    {
        $this->doTest('7d.json');
    }

    public function test_7e(): void
    {
        $this->doTest('7e.json');
    }

    public function test_7f(): void
    {
        $this->doTest('7f.json');
    }

    //

    public function test_80(): void
    {
        $this->doTest('80.json');
    }

    public function test_81(): void
    {
        $this->doTest('81.json');
    }

    public function test_82(): void
    {
        $this->doTest('82.json');
    }

    public function test_83(): void
    {
        $this->doTest('83.json');
    }

    public function test_84(): void
    {
        $this->doTest('84.json');
    }

    public function test_85(): void
    {
        $this->doTest('85.json');
    }

    public function test_86(): void
    {
        $this->doTest('86.json');
    }

    public function test_87(): void
    {
        $this->doTest('87.json');
    }

    public function test_88(): void
    {
        $this->doTest('88.json');
    }

    public function test_89(): void
    {
        $this->doTest('89.json');
    }

    public function test_8a(): void
    {
        $this->doTest('8a.json');
    }

    // TODO: unstable instruction
    /*
    public function test_8b(): void
    {
        $this->doTest('8b.json');
    }
    */

    public function test_8c(): void
    {
        $this->doTest('8c.json');
    }

    public function test_8d(): void
    {
        $this->doTest('8d.json');
    }

    public function test_8e(): void
    {
        $this->doTest('8e.json');
    }

    public function test_8f(): void
    {
        $this->doTest('8f.json');
    }

    //

    public function test_90(): void
    {
        $this->doTest('90.json');
    }

    public function test_91(): void
    {
        $this->doTest('91.json');
    }

    public function test_92(): void
    {
        $this->doTest('92.json');
    }

    // TODO: unstable instruction
    /*
    public function test_93(): void
    {
        $this->doTest('93.json');
    }
    */

    public function test_94(): void
    {
        $this->doTest('94.json');
    }

    public function test_95(): void
    {
        $this->doTest('95.json');
    }

    public function test_96(): void
    {
        $this->doTest('96.json');
    }

    public function test_97(): void
    {
        $this->doTest('97.json');
    }

    public function test_98(): void
    {
        $this->doTest('98.json');
    }

    public function test_99(): void
    {
        $this->doTest('99.json');
    }

    public function test_9a(): void
    {
        $this->doTest('9a.json');
    }

    // TODO: unstable instruction
    /*
    public function test_9b(): void
    {
        $this->doTest('9b.json');
    }
    */

    // TODO: unstable instruction
    /*
    public function test_9c(): void
    {
        $this->doTest('9c.json');
    }
    */

    public function test_9d(): void
    {
        $this->doTest('9d.json');
    }

    // TODO: unstable instruction
    /*
    public function test_9e(): void
    {
        $this->doTest('9e.json');
    }
    */

    // TODO: unstable instruction
    /*
    public function test_9f(): void
    {
        $this->doTest('9f.json');
    }
    */

    //

    public function test_a0(): void
    {
        $this->doTest('a0.json');
    }

    public function test_a1(): void
    {
        $this->doTest('a1.json');
    }

    public function test_a2(): void
    {
        $this->doTest('a2.json');
    }

    public function test_a3(): void
    {
        $this->doTest('a3.json');
    }

    public function test_a4(): void
    {
        $this->doTest('a4.json');
    }

    public function test_a5(): void
    {
        $this->doTest('a5.json');
    }

    public function test_a6(): void
    {
        $this->doTest('a6.json');
    }

    public function test_a7(): void
    {
        $this->doTest('a7.json');
    }

    public function test_a8(): void
    {
        $this->doTest('a8.json');
    }

    public function test_a9(): void
    {
        $this->doTest('a9.json');
    }

    public function test_aa(): void
    {
        $this->doTest('aa.json');
    }

    // TODO: unstable instruction
    /*
    public function test_ab(): void
    {
        $this->doTest('ab.json');
    }
    */

    public function test_ac(): void
    {
        $this->doTest('ac.json');
    }

    public function test_ad(): void
    {
        $this->doTest('ad.json');
    }

    public function test_ae(): void
    {
        $this->doTest('ae.json');
    }

    public function test_af(): void
    {
        $this->doTest('af.json');
    }

    //

    public function test_b0(): void
    {
        $this->doTest('b0.json');
    }

    public function test_b1(): void
    {
        $this->doTest('b1.json');
    }

    public function test_b2(): void
    {
        $this->doTest('b2.json');
    }

    public function test_b3(): void
    {
        $this->doTest('b3.json');
    }

    public function test_b4(): void
    {
        $this->doTest('b4.json');
    }

    public function test_b5(): void
    {
        $this->doTest('b5.json');
    }

    public function test_b6(): void
    {
        $this->doTest('b6.json');
    }

    public function test_b7(): void
    {
        $this->doTest('b7.json');
    }

    public function test_b8(): void
    {
        $this->doTest('b8.json');
    }

    public function test_b9(): void
    {
        $this->doTest('b9.json');
    }

    public function test_ba(): void
    {
        $this->doTest('ba.json');
    }

    public function test_bb(): void
    {
        $this->doTest('bb.json');
    }

    public function test_bc(): void
    {
        $this->doTest('bc.json');
    }

    public function test_bd(): void
    {
        $this->doTest('bd.json');
    }

    public function test_be(): void
    {
        $this->doTest('be.json');
    }

    public function test_bf(): void
    {
        $this->doTest('bf.json');
    }

    //

    public function test_c0(): void
    {
        $this->doTest('c0.json');
    }

    public function test_c1(): void
    {
        $this->doTest('c1.json');
    }

    public function test_c2(): void
    {
        $this->doTest('c2.json');
    }

    public function test_c3(): void
    {
        $this->doTest('c3.json');
    }

    public function test_c4(): void
    {
        $this->doTest('c4.json');
    }

    public function test_c5(): void
    {
        $this->doTest('c5.json');
    }

    public function test_c6(): void
    {
        $this->doTest('c6.json');
    }

    public function test_c7(): void
    {
        $this->doTest('c7.json');
    }

    public function test_c8(): void
    {
        $this->doTest('c8.json');
    }

    public function test_c9(): void
    {
        $this->doTest('c9.json');
    }

    public function test_ca(): void
    {
        $this->doTest('ca.json');
    }

    public function test_cb(): void
    {
        $this->doTest('cb.json');
    }

    public function test_cc(): void
    {
        $this->doTest('cc.json');
    }

    public function test_cd(): void
    {
        $this->doTest('cd.json');
    }

    public function test_ce(): void
    {
        $this->doTest('ce.json');
    }

    public function test_cf(): void
    {
        $this->doTest('cf.json');
    }

    //

    public function test_d0(): void
    {
        $this->doTest('d0.json');
    }

    public function test_d1(): void
    {
        $this->doTest('d1.json');
    }

    public function test_d2(): void
    {
        $this->doTest('d2.json');
    }

    public function test_d3(): void
    {
        $this->doTest('d3.json');
    }

    public function test_d4(): void
    {
        $this->doTest('d4.json');
    }

    public function test_d5(): void
    {
        $this->doTest('d5.json');
    }

    public function test_d6(): void
    {
        $this->doTest('d6.json');
    }

    public function test_d7(): void
    {
        $this->doTest('d7.json');
    }

    public function test_d8(): void
    {
        $this->doTest('d8.json');
    }

    public function test_d9(): void
    {
        $this->doTest('d9.json');
    }

    public function test_da(): void
    {
        $this->doTest('da.json');
    }

    public function test_db(): void
    {
        $this->doTest('db.json');
    }

    public function test_dc(): void
    {
        $this->doTest('dc.json');
    }

    public function test_dd(): void
    {
        $this->doTest('dd.json');
    }

    public function test_de(): void
    {
        $this->doTest('de.json');
    }

    public function test_df(): void
    {
        $this->doTest('df.json');
    }

    //

    public function test_e0(): void
    {
        $this->doTest('e0.json');
    }

    public function test_e1(): void
    {
        $this->doTest('e1.json');
    }

    public function test_e2(): void
    {
        $this->doTest('e2.json');
    }

    public function test_e3(): void
    {
        $this->doTest('e3.json');
    }

    public function test_e4(): void
    {
        $this->doTest('e4.json');
    }

    public function test_e5(): void
    {
        $this->doTest('e5.json');
    }

    public function test_e6(): void
    {
        $this->doTest('e6.json');
    }

    public function test_e7(): void
    {
        $this->doTest('e7.json');
    }

    public function test_e8(): void
    {
        $this->doTest('e8.json');
    }

    public function test_e9(): void
    {
        $this->doTest('e9.json');
    }

    public function test_ea(): void
    {
        $this->doTest('ea.json');
    }

    public function test_eb(): void
    {
        $this->doTest('eb.json');
    }

    public function test_ec(): void
    {
        $this->doTest('ec.json');
    }

    public function test_ed(): void
    {
        $this->doTest('ed.json');
    }

    public function test_ee(): void
    {
        $this->doTest('ee.json');
    }

    public function test_ef(): void
    {
        $this->doTest('ef.json');
    }

    //

    public function test_f0(): void
    {
        $this->doTest('f0.json');
    }

    public function test_f1(): void
    {
        $this->doTest('f1.json');
    }

    public function test_f2(): void
    {
        $this->doTest('f2.json');
    }

    public function test_f3(): void
    {
        $this->doTest('f3.json');
    }

    public function test_f4(): void
    {
        $this->doTest('f4.json');
    }

    public function test_f5(): void
    {
        $this->doTest('f5.json');
    }

    public function test_f6(): void
    {
        $this->doTest('f6.json');
    }

    public function test_f7(): void
    {
        $this->doTest('f7.json');
    }

    public function test_f8(): void
    {
        $this->doTest('f8.json');
    }

    public function test_f9(): void
    {
        $this->doTest('f9.json');
    }

    public function test_fa(): void
    {
        $this->doTest('fa.json');
    }

    public function test_fb(): void
    {
        $this->doTest('fb.json');
    }

    public function test_fc(): void
    {
        $this->doTest('fc.json');
    }

    public function test_fd(): void
    {
        $this->doTest('fd.json');
    }

    public function test_fe(): void
    {
        $this->doTest('fe.json');
    }

    public function test_ff(): void
    {
        $this->doTest('ff.json');
    }
}
