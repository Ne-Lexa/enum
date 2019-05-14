<?php
/** @noinspection MagicMethodsValidityInspection */
declare(strict_types=1);

namespace Nelexa;

/**
 * Based functional of Enum type.
 *
 * @author Ne-Lexa alexey@nelexa.ru
 * @license MIT
 */
abstract class Enum
{
    /**
     * contains cache already created by enum.
     *
     * @var self[][]
     * @internal
     */
    private static $instances = [];
    /**
     * the name of the constant
     *
     * @var string
     */
    private $name;
    /**
     * the enum scalar value of the constant
     *
     * @var string|int|float|bool|array|null
     */
    private $value;

    /**
     * Enum constructor.
     *
     * @param string $name the name of the constant
     * @param string|int|float|bool|array|null $value the enum scalar value of the constant
     */
    final private function __construct(string $name, $value)
    {
        $this->name = $name;
        $this->value = $value;
        $this->initValue($value);
    }

    /**
     * In this method, you can initialize additional variables based on the
     * value of the constant. The method is called after the constructor.
     *
     * @param string|int|float|bool|array|null $value the enum scalar value of the constant
     */
    protected function initValue($value): void
    {
    }

    /**
     * Returns the enum constant of the specified name.
     * The name must match exactly an identifier used to declare an enum constant
     * in this type. (Extraneous whitespace characters are not permitted.)
     *
     * @param string $name the name of the constant
     * @param array $arguments arguments (ignored)
     * @return static the enum constant of the specified enum type with the specified name
     */
    final public static function __callStatic(string $name, $arguments): self
    {
        return self::valueOf($name);
    }

    /**
     * Returns the enum constant of the specified name.
     * The name must match exactly an identifier used to declare an enum constant
     * in this type. (Extraneous whitespace characters are not permitted.)
     *
     * @param string $name the name of the constant
     * @return static the enum constant of the specified enum type with the specified name
     */
    final public static function valueOf(string $name): self
    {
        if (isset(self::$instances[static::class][$name])) {
            return self::$instances[static::class][$name];
        }
        $constants = self::getEnumConstants();
        if (!array_key_exists($name, $constants)) {
            throw new \InvalidArgumentException(sprintf(
                'Constant named "%s" is not defined in the %s class.',
                $name,
                static::class
            ));
        }
        return self::$instances[static::class][$name] = new static($name, $constants[$name]);
    }

    /**
     * Returns an array with class constants.
     *
     * @return array constants array
     */
    private static function getEnumConstants(): array
    {
        static $enumConstants = [];
        if (!isset($enumConstants[static::class])) {
            try {
                $reflectionClass = new \ReflectionClass(static::class);
                $enumConstants[static::class] = $reflectionClass->getConstants();
            } catch (\ReflectionException $e) {
                throw new \RuntimeException($e->getMessage(), $e->getCode(), $e);
            }
        }
        return $enumConstants[static::class];
    }

    /**
     * Returns the name of this enum constant.
     *
     * @return string the name of this enum constant
     */
    final public function name(): string
    {
        return $this->name;
    }

    /**
     * Returns the scalar value of this enum constant.
     *
     * @return string|int|float|bool|array|null the scalar value of this enum constant
     */
    final public function value()
    {
        return $this->value;
    }

    /**
     * Returns an array containing the constants of this enum
     * type, in the order they're declared. This method may be
     * used to iterate over the constants as follows:
     *
     *    foreach(EnumClass::values() as $enum)
     *        echo $enum->name() .' => ' . $enum->value() . PHP_EOL;
     *
     * @return static[] an array containing the constants of this enum
     *                  type, in the order they're declared
     */
    final public static function values(): array
    {
        return array_map('self::valueOf', array_keys(self::getEnumConstants()));
    }

    /**
     * Checks whether the constant name is present in the enum.
     *
     * @param string $name
     * @return bool returns true if the name is defined in one of the constants.
     */
    final public static function containsKey(string $name): bool
    {
        return isset(self::getEnumConstants()[$name]);
    }

    /**
     * Checks if enum contains a passed value.
     *
     * @param mixed $value check value
     * @param bool $strict strict check
     * @return bool returns true if the value is defined in one of the constants.
     */
    final public static function containsValue($value, bool $strict = true): bool
    {
        return in_array($value, self::getEnumConstants(), $strict);
    }

    /**
     * Returns the ordinal of this enumeration constant (its position in its enum
     * declaration, where the initial constant is assigned an ordinal of zero).
     *
     * @return int the ordinal of this enumeration constant
     */
    final public function ordinal(): int
    {
        return array_search($this->name, array_keys(self::getEnumConstants()), true);
    }

    /**
     * Returns the value of this enum constant, as contained in the declaration.
     * This method may be overridden, though it typically isn't necessary or desirable.
     * An enum type should override this method when a more "programmer-friendly"
     * string form exists.
     *
     * @return string the value of this enum constant (the array will be serialized in json)
     */
    public function __toString()
    {
        if (is_array($this->value)) {
            return (string)json_encode($this->value);
        }
        return (string)$this->value;
    }

    /**
     * Throws \LogicException. This guarantees that enums are never cloned,
     * which is necessary to preserve their "singleton" status.
     *
     * @throws \LogicException
     * @internal
     */
    final public function __clone()
    {
        throw new \LogicException('Enums are not cloneable');
    }

    /**
     * Protects the object from mutability and prevents the setting
     * of new properties for the object.
     *
     * @param $name
     * @param $value
     * @internal
     */
    final public function __set($name, $value)
    {
    }
}
