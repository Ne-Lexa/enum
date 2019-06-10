<?php
declare(strict_types=1);

namespace Nelexa;

/**
 * Generates an enum php doc block for describing static methods.
 *
 * @param Enum|string $enumOrEnumClass the enum instance or string enum class name
 * @return string php doc block for enum class
 */
function enum_docblock($enumOrEnumClass): string
{
    if (!is_a($enumOrEnumClass, Enum::class, true)) {
        throw new \InvalidArgumentException(sprintf(
            'Expected object or class name that is a descendant of the %s class.',
            Enum::class
        ));
    }

    $docBlock = '/**' . PHP_EOL;
    /**
     * @var Enum $enum
     */
    foreach (call_user_func([$enumOrEnumClass, 'values']) as $enum) {
        $docBlock .= ' * @method static self ' . $enum->name() . '()' . PHP_EOL;
    }
    $docBlock .= ' */' . PHP_EOL;
    return $docBlock;
}
