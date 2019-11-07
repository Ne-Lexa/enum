# nelexa/enum - Enum implementation for PHP

[![Packagist Version](https://img.shields.io/packagist/v/nelexa/enum.svg)](https://packagist.org/packages/nelexa/enum)
[![Packagist](https://img.shields.io/packagist/dt/nelexa/enum.svg?color=%23ff007f)](https://packagist.org/packages/nelexa/enum)
[![Build Status](https://travis-ci.org/Ne-Lexa/enum.svg?branch=master)](https://travis-ci.org/Ne-Lexa/enum)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Ne-Lexa/enum/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Ne-Lexa/enum/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/Ne-Lexa/enum/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Ne-Lexa/enum/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/Ne-Lexa/enum/badges/build.png?b=master)](https://scrutinizer-ci.com/g/Ne-Lexa/enum/build-status/master)
[![License](https://img.shields.io/packagist/l/nelexa/enum.svg)](https://packagist.org/packages/nelexa/enum)

Table of Contents
=================
   * [nelexa/enum - Enum implementation for PHP](#nelexaenum---enum-implementation-for-php)
   * [Table of Contents](#table-of-contents)
   * [Installation](#installation)
   * [Enum declaration](#enum-declaration)
   * [Usage](#usage)
      * [To loop a enum object](#to-loop-a-enum-object)
      * [To compare the enum values, use === operator](#to-compare-the-enum-values-use--operator)
      * [Convert a string to enum object](#convert-a-string-to-enum-object)
      * [Convert a value to enum object](#convert-a-value-to-enum-object)
      * [Switch case](#switch-case)
      * [Use enum in the type hint](#use-enum-in-the-type-hint)
      * [Add some logic to enum](#add-some-logic-to-enum)
      * [Initialization of values ​​without constructor](#initialization-of-values-without-constructor)
   * [Class Synopsis](#class-synopsis)
   * [Usage tips](#usage-tips)
   * [Generate PHPDoc for enum class](#generate-phpdoc-for-enum-class)
   * [Changelog](#changelog)
   * [License](#license)

# Installation
```bash
composer require nelexa/enum
```

# Enum declaration
```php
<?php

use Nelexa\Enum;

/**
 * @method static self PENDING()
 * @method static self ACTIVE()
 * @method static self INACTIVE()
 * @method static self DELETED()
 */
class UserStatus extends Enum
{
    public const
        PENDING = 1,
        ACTIVE = 1 << 1,
        INACTIVE = 1 << 2,
        DELETED = 1 << 3;
}
```

# Usage
```php
$enum = UserStatus::ACTIVE();

assert($enum instanceof UserStatus);
assert($enum->name() === 'ACTIVE');
assert($enum->value() === 1 << 1);
assert($enum->ordinal() === 1);
```

## To loop a enum object
```php
foreach (UserStatus::values() as $userStatus) {
    printf('User status: %s, status id: %d, ordinal: %d' . PHP_EOL,
        $userStatus->name(),
        $userStatus->value(),
        $userStatus->ordinal()
    );
}
```
Output:
```
User status: PENDING, status id: 1, ordinal: 0
User status: ACTIVE, status id: 2, ordinal: 1
User status: INACTIVE, status id: 4, ordinal: 2
User status: DELETED, status id: 8, ordinal: 3
```

## To compare the enum values, use `===` operator
```php
$userStatus = UserStatus::DELETED();
if ($userStatus === UserStatus::DELETED()) {
    echo 'User status: ' . $userStatus->name() . PHP_EOL;
}
```

## Convert a string to enum object
```php
$enum = UserStatus::valueOf('ACTIVE');

assert(UserStatus::ACTIVE() === UserStatus::valueOf('ACTIVE'));
```

## Convert a value to enum object
```php
$enum = UserStatus::valueOf('ACTIVE');
$value = $enum->value();

assert(UserStatus::fromValue($value) === $enum);
assert(UserStatus::fromValue($value) === UserStatus::valueOf('ACTIVE'));
```

## Switch case
```php
$enum = UserStatus::PENDING();
switch ($enum) {
    case UserStatus::ACTIVE():
        echo 'Active status';
        break;
    case UserStatus::PENDING():
        echo 'Pending status';
        break;
    case UserStatus::DELETED():
        echo 'Delete status';
        break;
    case UserStatus::INACTIVE():
        echo 'Inactive status';
        break;
    default:
        throw new \RuntimeException('Invalid value');
}
```
Output:
```
Pending status
```

## Use enum in the type hint
```php
/**
 * @var UserStatus
 */
private $status;

public function setStatus(UserStatus $status): void
{
    $this->status = $status;
}

public function getStatus(): UserStatus
{
    return $this->status;
}

public function isActive(): bool
{
    return $this->status === UserStatus::ACTIVE();
}
```
Example
```php
$user->setStatus(UserStatus::INACTIVE());
echo sprintf('User status is %s.' . PHP_EOL, $user->getStatus()->name());
```
Output:
```
User status is INACTIVE
```

## Add some logic to enum
```php
<?php

/**
 * @method static self PLUS()
 * @method static self MINUS()
 * @method static self TIMES()
 * @method static self DIVIDE()
 */
class Operation extends \Nelexa\Enum
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
```
Example
```php
echo Operation::PLUS()->calculate(4, 2);   // 6
echo Operation::TIMES()->calculate(4, 2);  // 8
echo Operation::MINUS()->calculate(4, 2);  // 2
echo Operation::DIVIDE()->calculate(4, 2); // 2
```

## Initialization of values ​​without constructor
For example consider the planets of the solar system. Each planet knows its mass and radius, and can calculate its surface gravity and the weight of an object on the planet. 

Here is how it looks:
```php
<?php
declare(strict_types=1);

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

    public function mass(): float
    {
        return $this->mass;
    }

    public function radius(): float
    {
        return $this->radius;
    }

    public function surfaceGravity(): float
    {
        return self::$G * $this->mass / ($this->radius * $this->radius);
    }

    public function surfaceWeight(float $otherMass): float
    {
        return round($otherMass * $this->surfaceGravity(), 6);
    }
}
```
The enum Planet class contains the `initValue($value)` method, and each enum constant is declared with a value that to be passed to this method when it is created.

Here is a sample program that takes your weight on earth (in any unit) and calculates and prints your weight on all of the planets (in the same unit):
```php
$earthWeight = 175;
$mass = $earthWeight / Planet::EARTH()->surfaceGravity();
foreach (Planet::values() as $p) {
    printf("Your weight on %s is %f\n", $p->name(), $p->surfaceWeight($mass));
}
```
Output:
```
Your weight on MERCURY is 66.107583
Your weight on VENUS is 158.374842
Your weight on EARTH is 175.000000
Your weight on MARS is 66.279007
Your weight on JUPITER is 442.847567
Your weight on SATURN is 186.552719
Your weight on URANUS is 158.397260
Your weight on NEPTUNE is 199.207413
Your weight on PLUTO is 11.703031
```

## Class Synopsis
```php
abstract class Nelexa\Enum {

    /* Methods */
    final public static valueOf ( string $name ) : static
    final public name ( void ) : string
    final public value ( void ) : string | int | float | bool | array | null
    final public static values ( void ) : static[]
    final public static containsKey ( string $name ) : bool
    final public static containsValue ( mixed $value [, bool $strict = true ] ) : bool
    final public static function fromValue( mixed $value ): static
    final public ordinal ( void ) : int
    public __toString ( void ) : string
    protected static function getEnumConstants(): array
}
```

# Usage tips
* Even though it is not mandatory to declare enum constants with **UPPERCASE** letters, it is in the best practice to do so.
* Enum classes can have fields and methods along with enum constants.
* Enum constructors are **private**. Only private constructors are allowed in enum classes. That’s why you can’t instantiate enum types using a **new** operator.
* Enum constants are created only once for the whole execution. All enum constants are created when you initially refer any enum constant in your code. 
* Enum types can implement any number of interfaces.
* We can compare the enum constants using the `===` operator.
* You can retrieve the enum constants of any enum type using the `values()` static method. The `values()` static method returns an array of enum constants.
* The `ordinal()` static method is used to get the order of an enum constant in an enum type.
* Enums are mostly used when you want to allow a limited set of options that remain constant for the whole execution and you know all possible options. For example, this could be **choices on a menu** or **options of a combobox**.
* Use private constants to exclude prompts from your IDE.
* Use PHPDoc to describe static enum initialization methods.

# Generate PHPDoc for enum class
To use hints in the IDE, you need to declare special comments on the class with a list of static methods.

The `\Nelexa\enum_docblock($enumOrEnumClass)` function should help with this.

```php
echo \Nelexa\enum_docblock(Planet::class);
// or
echo \Nelexa\enum_docblock(Planet::MERCURY());
```
Output:
```
/**
 * @method static self MERCURY()
 * @method static self VENUS()
 * @method static self EARTH()
 * @method static self MARS()
 * @method static self JUPITER()
 * @method static self SATURN()
 * @method static self URANUS()
 * @method static self NEPTUNE()
 * @method static self PLUTO()
 */
```

# Changelog

Changes are documented in the [releases page](https://github.com/Ne-Lexa/enum/releases).

# License

The files in this archive are released under the `MIT License`.
 
You can find a copy of this license in `LICENSE` file.
