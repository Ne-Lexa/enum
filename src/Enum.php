<?php
/** @noinspection MagicMethodsValidityInspection */
declare(strict_types=1);

/**
 * @author   Ne-Lexa
 * @license  MIT
 * @link     https://github.com/Ne-Lexa/enum
 */

namespace Nelexa;

/**
 * Based functional of Enum type.
 */
abstract class Enum
{
    /**
     * Contains cache already created by enum.
     *
     * @var self[][]
     * @internal
     */
    private static $instances = [];

    /** @var string Constant name. */
    private $name;

    /** @var string|int|float|bool|array|null Constant value */
    private $value;

    /**
     * Enum constructor.
     *
     * @param string $name Constant name.
     * @param string|int|float|bool|array|null $value Constant value.
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
     * @param string|int|float|bool|array|null $value Constant value.
     */
    protected function initValue($value): void
    {
    }

    /**
     * Returns the enum of the specified constant name.
     *
     * The name must match exactly an identifier used to declare an enum constant
     * in this type. (Extraneous whitespace characters are not permitted.)
     *
     * @param string $name Constant name.
     * @param array $arguments Arguments (currently not used).
     *
     * @return static Object of subtype Enum.
     *
     * @internal
     */
    final public static function __callStatic(string $name, $arguments): self
    {
        return self::valueOf($name);
    }

    /**
     * Returns the enum of the specified constant name.
     *
     * The name must match exactly an identifier used to declare an enum constant
     * in this type. (Extraneous whitespace characters are not permitted.)
     *
     * @param string $name the name of the constant
     *
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
     * @return array Array of constants.
     */
    protected static function getEnumConstants(): array
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
     * @return string Constant name.
     */
    final public function name(): string
    {
        return $this->name;
    }

    /**
     * Returns the scalar value of this enum constant.
     *
     * @return string|int|float|bool|array|null Constant value.
     */
    final public function value()
    {
        return $this->value;
    }

    /**
     * Returns an array containing the constants of this enum type, in the order they're declared.
     *
     * This method may be used to iterate over the constants as follows:
     *
     * ```php
     * foreach(EnumClass::values() as $enum) {
     *     echo $enum->name() . ' => ' . $enum->value() . PHP_EOL;
     * }
     * ```
     *
     * @return static[] An array of constants of this type enum in the order they are declared.
     */
    final public static function values(): array
    {
        return array_map('self::valueOf', array_keys(self::getEnumConstants()));
    }

    /**
     * Checks whether the constant name is present in the enum.
     *
     * @param string $name Constant name.
     *
     * @return bool Returns true if the name is defined in one of the constants.
     */
    final public static function containsKey(string $name): bool
    {
        return isset(self::getEnumConstants()[$name]);
    }

    /**
     * Checks if enum contains a passed value.
     *
     * @param string|int|float|bool|array|null $value Checked value.
     * @param bool $strict Strict check.
     *
     * @return bool Returns true if the value is defined in one of the constants.
     */
    final public static function containsValue($value, bool $strict = true): bool
    {
        return in_array($value, self::getEnumConstants(), $strict);
    }

    /**
     * Returns first enum of the specified constant value.
     *
     * @param string|int|float|bool|array|null $value Checked value.
     *
     * @return static the enum constant of the specified constant value.
     */
    final public static function fromValue($value): self
    {
        $key = array_search($value, self::getEnumConstants(), true);
        if ($key === false) {
            throw new \InvalidArgumentException(sprintf(
                'Constant value "%s" is not defined in the %s class.',
                $value,
                static::class
            ));
        }
        return self::valueOf($key);
    }

    /**
     * Returns the ordinal of this enum constant.
     *
     * The first constant is assigned a sequence number of zero.
     *
     * @return int Ordinal of this enumeration constant.
     */
    final public function ordinal(): int
    {
        $key = array_search($this->name, array_keys(self::getEnumConstants()), true);
        if ($key === false) {
            throw new \RuntimeException(sprintf('Not found the ordinal number of the constant %s', $this->name));
        }
        return $key;
    }

    /**
     * Returns the value of this enum constant, as contained in the declaration.
     *
     * This method may be overridden, though it typically isn't necessary or desirable.
     * An enum type should override this method when a more "programmer-friendly"
     * string form exists.
     *
     * @return string Value of this enum constant (the array will be serialized in json).
     */
    public function __toString()
    {
        if (is_array($this->value)) {
            return (string)json_encode($this->value);
        }
        return (string)$this->value;
    }

    /**
     * Enum cloning.
     *
     * This method guarantees that enums are never cloned,
     * which is necessary to preserve their "singleton" status.
     *
     * @throws \LogicException Always throw an exception.
     *
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
     * @param mixed $name Name
     * @param mixed $value Value
     *
     * @internal
     */
    final public function __set($name, $value)
    {
    }
}
