<?php
/** @noinspection AccessModifierPresentedInspection */
declare(strict_types=1);

namespace Nelexa\Tests\Enums;

use Nelexa\Enum;

/**
 * @method static Enum TOP
 * @method static Enum NEW
 */
class OverrideToStringEnum extends Enum
{
    const
        TOP = 'category_top',
        NEW = 'category_new';

    public function __toString()
    {
        return $this->name();
    }
}
