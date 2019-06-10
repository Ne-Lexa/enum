<?php
/** @noinspection PhpUnusedPrivateFieldInspection */
declare(strict_types=1);

namespace Nelexa\Tests\Enums;

use Nelexa\Enum;

/**
 * @method static self PLUS()
 * @method static self MINUS()
 * @method static self TIMES()
 * @method static self DIVIDE()
 */
class Operation extends Enum
{
    private const
        PLUS = null,
        MINUS = null,
        TIMES = null,
        DIVIDE = null;

    /**
     * Do arithmetic op represented by this constant
     *
     * @param float $x
     * @param float $y
     * @return float
     */
    public function calculate(float $x, float $y): float
    {
        switch ($this) {
            case self::PLUS():
                return $x + $y;
            case self::MINUS():
                return $x - $y;
            case self::TIMES():
                return $x * $y;
            case self::DIVIDE():
                return $x / $y;
        }
        throw new \AssertionError('Unknown op: ' . $this->name());
    }
}
