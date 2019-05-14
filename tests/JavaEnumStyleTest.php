<?php
declare(strict_types=1);

namespace Nelexa\Tests;

use Nelexa\Tests\Enums\Operation;
use Nelexa\Tests\Enums\Planet;
use PHPUnit\Framework\TestCase;

/**
 * @see https://docs.oracle.com/javase/8/docs/technotes/guides/language/enums.html
 */
class JavaEnumStyleTest extends TestCase
{
    public function testPlanet(): void
    {
        $earthWeight = 175;
        $mass = $earthWeight / Planet::EARTH()->surfaceGravity();

        $actualResults = [
            'MERCURY' => 66.107583,
            'VENUS' => 158.374842,
            'EARTH' => 175.000000,
            'MARS' => 66.279007,
            'JUPITER' => 442.847567,
            'SATURN' => 186.552719,
            'URANUS' => 158.397260,
            'NEPTUNE' => 199.207413,
            'PLUTO' => 11.703031,
        ];

        foreach (Planet::values() as $planet) {
            $this->assertArrayHasKey($planet->name(), $actualResults);
            $actualResult = $actualResults[$planet->name()];
            $this->assertEquals($planet->surfaceWeight($mass), $actualResult);
        }
    }

    /**
     * @dataProvider provideOperation
     * @param float|int $x
     * @param float|int $y
     * @param array $actualData
     */
    public function testOperation($x, $y, $actualData): void
    {
        foreach ($actualData as $op => $result) {
            $operation = Operation::valueOf($op);
            $this->assertEquals($operation->calculate($x, $y), $result);
        }
    }

    public function provideOperation(): array
    {
        return [
            [
                4,
                2,
                [
                    'PLUS' => 6.000000,
                    'MINUS' => 2.000000,
                    'TIMES' => 8.000000,
                    'DIVIDE' => 2.000000,
                ],
            ],
        ];
    }
}
