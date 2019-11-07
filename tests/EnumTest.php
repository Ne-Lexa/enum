<?php
declare(strict_types=1);

namespace Nelexa\Tests;

use Nelexa\Tests\Enums\EnumExtended;
use Nelexa\Tests\Enums\ExampleEnum;
use Nelexa\Tests\Enums\OverrideToStringEnum;
use PHPUnit\Framework\TestCase;

class EnumTest extends TestCase
{
    public function testSameEnum(): void
    {
        $o = OverrideToStringEnum::valueOf('NEW');
        $objects = [
            OverrideToStringEnum::valueOf('NEW'),
            call_user_func([OverrideToStringEnum::class, 'valueOf'], 'NEW'),
            call_user_func([OverrideToStringEnum::class, 'valueOf'], 'NEW'),
            OverrideToStringEnum::NEW(),
            OverrideToStringEnum::NEW(),
            call_user_func([OverrideToStringEnum::class, 'NEW']),
            call_user_func([OverrideToStringEnum::class, 'NEW']),
        ];

        foreach ($objects as $another) {
            $this->assertSame($another, $o);
        }
    }

    /**
     * @dataProvider provideEnum
     *
     * @param string $name
     * @param mixed $value
     * @param int $ordinal
     */
    public function testEnum(string $name, $value, int $ordinal): void
    {
        $enum = ExampleEnum::valueOf($name);
        $this->assertEquals($enum->value(), $value);
        $this->assertEquals($enum->name(), $name);
        $this->assertEquals($enum->ordinal(), $ordinal);
        // test singleton objects
        $this->assertSame(ExampleEnum::valueOf($name), $enum);
        $this->assertSame(call_user_func([ExampleEnum::class, $name]), $enum);
        // test to string
        $this->assertEquals((string)$enum, is_array($value) ? json_encode($value) : $value);
    }

    /**
     * @return array
     */
    public function provideEnum(): array
    {
        return [
            ['VALUE_INT', ExampleEnum::VALUE_INT, 0],
            ['VALUE_INT_1000', ExampleEnum::VALUE_INT_1000, 1],
            ['VALUE_STRING', ExampleEnum::VALUE_STRING, 2],
            ['VALUE_BOOL_TRUE', ExampleEnum::VALUE_BOOL_TRUE, 3],
            ['VALUE_BOOL_FALSE', ExampleEnum::VALUE_BOOL_FALSE, 4],
            ['VALUE_FLOAT', ExampleEnum::VALUE_FLOAT, 5],
            ['VALUE_NULL', ExampleEnum::VALUE_NULL, 6],
            ['VALUE_EMPTY_STRING', ExampleEnum::VALUE_EMPTY_STRING, 7],
            ['VALUE_EQUALS_1', ExampleEnum::VALUE_EQUALS_1, 8],
            ['VALUE_EQUALS_2', ExampleEnum::VALUE_EQUALS_2, 9],
            ['SCALAR_EXPRESSION', ExampleEnum::SCALAR_EXPRESSION, 10],
            ['CONST', ExampleEnum::CONST, 11],
            ['PUBLIC_CONST', ExampleEnum::PUBLIC_CONST, 12],
            ['PRIVATE_CONST', 'private', 13],
            ['PROTECTED_CONST', 'protected', 14],
            ['LANG_CODES', ExampleEnum::LANG_CODES, 15],
            ['COUNTRY_CODES', ExampleEnum::COUNTRY_CODES, 16],
        ];
    }

    /**
     * @throws \ReflectionException
     */
    public function testValues(): void
    {
        $reflectionConstants = (new \ReflectionClass(ExampleEnum::class))
            ->getConstants();

        $constants = [];
        $enums = ExampleEnum::values();
        foreach ($enums as $enum) {
            $constants[$enum->name()] = $enum->value();
        }

        $this->assertEquals($constants, $reflectionConstants);
        $this->assertSame($enums, ExampleEnum::values());
    }

    public function testContains(): void
    {
        $this->assertTrue(ExampleEnum::containsKey('VALUE_INT'));
        $this->assertFalse(ExampleEnum::containsKey('VALUE_100500'));

        $this->assertTrue(ExampleEnum::containsValue(ExampleEnum::VALUE_BOOL_TRUE));
        $this->assertTrue(ExampleEnum::containsValue(ExampleEnum::VALUE_BOOL_FALSE));
        $this->assertTrue(ExampleEnum::containsValue(ExampleEnum::VALUE_NULL));
        $this->assertTrue(ExampleEnum::containsValue(ExampleEnum::VALUE_INT));
        $this->assertTrue(ExampleEnum::containsValue(ExampleEnum::VALUE_STRING));
        $this->assertTrue(ExampleEnum::containsValue(ExampleEnum::COUNTRY_CODES));
        $this->assertTrue(ExampleEnum::containsValue(ExampleEnum::VALUE_EQUALS_2));
        $this->assertFalse(ExampleEnum::containsValue('VALUE_100500'));

        $this->assertTrue(ExampleEnum::containsValue(ExampleEnum::VALUE_INT_1000()->value()));

        $this->assertTrue(ExampleEnum::containsValue(1000));
        $this->assertFalse(ExampleEnum::containsValue('1000'));
        $this->assertTrue(ExampleEnum::containsValue('1000', false));
    }

    public function testInvalidName(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Constant named "INVALID_VALUE" is not defined');

        ExampleEnum::valueOf('INVALID_VALUE');
    }

    public function testInvalidStaticMethod(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Constant named "INVALID_METHOD" is not defined');

        /** @noinspection PhpUndefinedMethodInspection */
        ExampleEnum::INVALID_METHOD();
    }

    public function testExtended(): void
    {
        $this->assertTrue(EnumExtended::containsKey('VALUE_EXTENDED'));
        $this->assertTrue(EnumExtended::containsKey('VALUE_INT'));

        $enumNames = array_map(static function (EnumExtended $value) {
            return $value->name();
        }, EnumExtended::values());

        $this->assertEquals($enumNames, [
            'VALUE_EXTENDED',
            'VALUE_INT',
            'VALUE_INT_1000',
            'VALUE_STRING',
            'VALUE_BOOL_TRUE',
            'VALUE_BOOL_FALSE',
            'VALUE_FLOAT',
            'VALUE_NULL',
            'VALUE_EMPTY_STRING',
            'VALUE_EQUALS_1',
            'VALUE_EQUALS_2',
            'SCALAR_EXPRESSION',
            'CONST',
            'PUBLIC_CONST',
            'PROTECTED_CONST',
            'LANG_CODES',
            'COUNTRY_CODES',
        ]);
    }

    public function testOverrideToString(): void
    {
        $this->assertEquals(OverrideToStringEnum::TOP(), 'TOP');
        $this->assertEquals(OverrideToStringEnum::NEW(), 'NEW');
    }

    public function testCloneEnum(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Enums are not cloneable');
        $enum = ExampleEnum::VALUE_STRING();
        /** @noinspection PhpUnusedLocalVariableInspection */
        $clonedEnum = clone $enum;
    }

    public function testSerializeEnum(): void
    {
        $enum = ExampleEnum::VALUE_STRING();
        $serializedEnumValue = serialize($enum);
        $expectedEnum = unserialize($serializedEnumValue);

        $this->assertEquals($expectedEnum, $enum);
        $this->assertNotSame($expectedEnum, $enum);
    }

    public function testEnumProperty(): void
    {
        $enum = ExampleEnum::VALUE_STRING();
        /** @noinspection PhpUndefinedFieldInspection */
        $enum->new_field_name = true;

        $this->assertFalse(property_exists($enum, 'new_field_name'));

        unset($enum->new_field_name); // __unset invoke
    }

    /**
     * @dataProvider provideEnumFromValue
     *
     * @param string $name
     * @param mixed $value
     */
    public function testEnumFromValue(string $name, $value): void
    {
        $enum = ExampleEnum::valueOf($name);

        $this->assertSame(ExampleEnum::fromValue($value), $enum);
    }

    /**
     * @return array
     */
    public function provideEnumFromValue(): array
    {
        return [
            ['VALUE_INT', ExampleEnum::VALUE_INT],
            ['VALUE_INT_1000', ExampleEnum::VALUE_INT_1000],
            ['VALUE_STRING', ExampleEnum::VALUE_STRING],
            ['VALUE_BOOL_TRUE', ExampleEnum::VALUE_BOOL_TRUE],
            ['VALUE_BOOL_FALSE', ExampleEnum::VALUE_BOOL_FALSE],
            ['VALUE_FLOAT', ExampleEnum::VALUE_FLOAT],
            ['VALUE_NULL', ExampleEnum::VALUE_NULL],
            ['VALUE_EMPTY_STRING', ExampleEnum::VALUE_EMPTY_STRING],
            ['SCALAR_EXPRESSION', ExampleEnum::SCALAR_EXPRESSION],
            ['CONST', ExampleEnum::CONST],
            ['PUBLIC_CONST', ExampleEnum::PUBLIC_CONST],
            ['PRIVATE_CONST', 'private'],
            ['PROTECTED_CONST', 'protected'],
            ['LANG_CODES', ExampleEnum::LANG_CODES],
            ['COUNTRY_CODES', ExampleEnum::COUNTRY_CODES],
        ];
    }

    public function testEnumFromValueForSameValues(): void
    {
        $this->assertSame(ExampleEnum::fromValue(ExampleEnum::VALUE_EQUALS_1), ExampleEnum::VALUE_EQUALS_1());
        $this->assertSame(ExampleEnum::fromValue(ExampleEnum::VALUE_EQUALS_2), ExampleEnum::VALUE_EQUALS_1());
        $this->assertNotSame(ExampleEnum::fromValue(ExampleEnum::VALUE_EQUALS_2), ExampleEnum::VALUE_EQUALS_2());
    }

    public function testEnumFromValueIncorrectValue(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Constant value "Unknown value" is not defined');

        ExampleEnum::fromValue('Unknown value');
    }
}
