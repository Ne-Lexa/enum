<?php
declare(strict_types=1);

namespace Nelexa\Tests;

use function Nelexa\enum_docblock;
use Nelexa\Tests\Enums\OverrideToStringEnum;
use PHPUnit\Framework\TestCase;

class EnumGenerationDocBlockTest extends TestCase
{
    public function testGenerationEnumDocBlockMethods(): void
    {
        $actualDocBlock = <<<'PHPDOC'
/**
 * @method static self TOP
 * @method static self NEW
 */

PHPDOC;

        $this->assertEquals(
            enum_docblock(OverrideToStringEnum::NEW()),
            $actualDocBlock
        );

        $this->assertEquals(
            enum_docblock(OverrideToStringEnum::class),
            $actualDocBlock
        );
    }

    public function testErrorGenerationEnumDocBlock(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected object or class name');

        enum_docblock(__CLASS__);
    }
}
