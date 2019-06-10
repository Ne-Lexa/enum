<?php
/** @noinspection PhpUnusedPrivateFieldInspection */
declare(strict_types=1);

namespace Nelexa\Tests\Enums;

use Nelexa\Enum;

/**
 * Class Planet
 *
 * @method static self MERCURY()
 * @method static self VENUS()
 * @method static self EARTH()
 * @method static self MARS()
 * @method static self JUPITER()
 * @method static self SATURN()
 * @method static self URANUS()
 * @method static self NEPTUNE()
 * @method static self PLUTO()
 *
 * @see https://docs.oracle.com/javase/8/docs/technotes/guides/language/enums.html
 */
class Planet extends Enum
{
    private const
        MERCURY = [3.303e+23, 2.4397e6],
        VENUS = [4.869e+24, 6.0518e6],
        EARTH = [5.976e+24, 6.37814e6],
        MARS = [6.421e+23, 3.3972e6],
        JUPITER = [1.9e+27, 7.1492e7],
        SATURN = [5.688e+26, 6.0268e7],
        URANUS = [8.686e+25, 2.5559e7],
        NEPTUNE = [1.024e+26, 2.4746e7],
        PLUTO = [1.27e+22, 1.137e6];

    /**
     * @var double universal gravitational constant (m3 kg-1 s-2)
     */
    private static $G = 6.67300E-11;

    /**
     * @var double in kilograms
     */
    private $mass;
    /**
     * @var double in meters
     */
    private $radius;

    /**
     * In this method, you can initialize additional variables based on the
     * value of the constant. The method is called after the constructor.
     *
     * @param string|int|float|bool|array|null $value the enum scalar value of the constant
     */
    protected function initValue($value): void
    {
        [$this->mass, $this->radius] = $value;
    }

    /**
     * @return float
     */
    public function mass(): float
    {
        return $this->mass;
    }

    /**
     * @return float
     */
    public function radius(): float
    {
        return $this->radius;
    }

    /**
     * @return float
     */
    public function surfaceGravity(): float
    {
        return self::$G * $this->mass / ($this->radius * $this->radius);
    }

    /**
     * @param float $otherMass
     * @return float
     */
    public function surfaceWeight(float $otherMass): float
    {
        return round($otherMass * $this->surfaceGravity(), 6);
    }
}
