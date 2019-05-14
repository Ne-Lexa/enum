<?php
/** @noinspection PhpUnusedPrivateFieldInspection */
/** @noinspection AccessModifierPresentedInspection */
declare(strict_types=1);

namespace Nelexa\Tests\Enums;

use Nelexa\Enum;

/**
 * @method static self VALUE_INT
 * @method static self VALUE_INT_1000
 * @method static self VALUE_STRING
 * @method static self VALUE_BOOL_TRUE
 * @method static self VALUE_BOOL_FALSE
 * @method static self VALUE_FLOAT
 * @method static self VALUE_NULL
 * @method static self VALUE_EMPTY_STRING
 * @method static self VALUE_EQUALS_1
 * @method static self VALUE_EQUALS_2
 * @method static self SCALAR_EXPRESSION
 * @method static self CONST
 * @method static self PUBLIC_CONST
 * @method static self PRIVATE_CONST
 * @method static self PROTECTED_CONST
 * @method static self LANG_CODES
 * @method static self COUNTRY_CODES
 */
class ExampleEnum extends Enum
{
    public const
        VALUE_INT = 0,
        VALUE_INT_1000 = 1000,
        VALUE_STRING = 'String Value',
        VALUE_BOOL_TRUE = true,
        VALUE_BOOL_FALSE = false,
        VALUE_FLOAT = 0.000324,
        VALUE_NULL = null,
        VALUE_EMPTY_STRING = '';

    public const VALUE_EQUALS_1 = 'equals value';
    public const VALUE_EQUALS_2 = 'equals value';

    public const SCALAR_EXPRESSION = self::VALUE_STRING . ' - ' . self::VALUE_INT_1000;

    const CONST = 'const';
    public const PUBLIC_CONST = 'public';
    private const PRIVATE_CONST = 'private';
    protected const PROTECTED_CONST = 'protected';

    const LANG_CODES = [
        'en',
        'fr',
        'de',
        'es',
        'ru',
        'zh',
    ];
    public const COUNTRY_CODES = [
        'us' => 'USA',
        'cn' => 'China',
        'fr' => 'France',
        'ru' => 'Russia',
    ];
}
