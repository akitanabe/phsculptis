<?php

declare(strict_types=1);

namespace PhSculptis\Test\Core\Validators;

use PhSculptis\Enums\PropertyInitializedStatus;
use PhSculptis\Enums\TypeHintType;
use PhSculptis\Support\PropertyMetadata;
use PhSculptis\Support\TypeHint;
use PhSculptis\Core\Validators\PrimitiveTypeValidator;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use TypeError;
use stdClass;

class PrimitiveTypeValidatorTest extends TestCase
{
    /**
     * インターセクション型かつオブジェクト値の場合、値をそのまま返すことを確認
     */
    #[Test]
    public function testValidateReturnsValueForIntersectionTypeAndObjectValue(): void
    {
        $typeHint = new TypeHint(TypeHintType::OBJECT, false, true);

        $metadata = $this->createPropertyMetadata([$typeHint]);
        $validator = new PrimitiveTypeValidator($metadata);
        $value = new stdClass();

        $result = $validator->validate($value);
        $this->assertSame($value, $result);
    }

    /**
     * プリミティブ型が存在しない場合、値をそのまま返すことを確認
     */
    #[Test]
    public function testValidateReturnsValueWhenNoPrimitiveTypes(): void
    {
        $metadata = $this->createPropertyMetadata([new TypeHint(TypeHintType::OBJECT, false, false)]);
        $validator = new PrimitiveTypeValidator($metadata);
        $value = new stdClass();

        $result = $validator->validate($value);
        $this->assertSame($value, $result);
    }

    /**
     * プリミティブ型と値の型が一致する場合、値をそのまま返すことを確認
     */
    #[Test]
    public function testValidateReturnsValueWhenTypesMatch(): void
    {
        $metadata = $this->createPropertyMetadata([new TypeHint(TypeHintType::STRING, true, false)]);
        $validator = new PrimitiveTypeValidator($metadata);
        $value = 'test_string';

        $result = $validator->validate($value);
        $this->assertEquals($value, $result);
    }

    /**
     * int型プロパティに対してint値を適用できることを確認
     */
    #[Test]
    public function testValidateIntegerValue(): void
    {
        $metadata = $this->createPropertyMetadata([new TypeHint(TypeHintType::INT, true, false)]);
        $validator = new PrimitiveTypeValidator($metadata);
        $value = 123;

        $result = $validator->validate($value);
        $this->assertSame($value, $result);
    }

    /**
     * bool型プロパティに対してbool値を適用できることを確認
     */
    #[Test]
    public function testValidateBooleanValue(): void
    {
        $metadata = $this->createPropertyMetadata([new TypeHint(TypeHintType::BOOL, true, false)]);
        $validator = new PrimitiveTypeValidator($metadata);
        $value = true;

        $result = $validator->validate($value);
        $this->assertSame($value, $result);
    }

    /**
     * float型プロパティに対してfloat値を適用できることを確認
     */
    #[Test]
    public function testValidateFloatValue(): void
    {
        $metadata = $this->createPropertyMetadata([new TypeHint(TypeHintType::FLOAT, true, false)]);
        $validator = new PrimitiveTypeValidator($metadata);
        $value = 123.45;

        $result = $validator->validate($value);
        $this->assertSame($value, $result);
    }

    /**
     * 複数のプリミティブ型が存在し、値がいずれかの型と一致する場合、値をそのまま返すことを確認
     */
    #[Test]
    public function testValidateReturnsValueWhenOneOfMultipleTypesMatch(): void
    {
        $metadata = $this->createPropertyMetadata([
            new TypeHint(TypeHintType::STRING, true, false),
            new TypeHint(TypeHintType::INT, true, false),
        ]);
        $validator = new PrimitiveTypeValidator($metadata);
        $value = 123;

        $result = $validator->validate($value);
        $this->assertSame($value, $result);
    }

    /**
     * プリミティブ型と値の型が一致しない場合、例外をスローすることを確認
     */
    #[Test]
    public function testValidateThrowsExceptionWhenTypesDoNotMatch(): void
    {
        $metadata = $this->createPropertyMetadata([new TypeHint(TypeHintType::INT, true, false)]);
        $validator = new PrimitiveTypeValidator($metadata);
        $value = 'not_an_int';

        $this->expectException(TypeError::class);
        $validator->validate($value);
    }

    /**
     * 複数のプリミティブ型が存在し、値がいずれの型とも一致しない場合、例外をスローすることを確認
     */
    #[Test]
    public function testValidateThrowsExceptionWhenNoTypesMatch(): void
    {
        $metadata = $this->createPropertyMetadata([
            new TypeHint(TypeHintType::INT, true, false),
            new TypeHint(TypeHintType::BOOL, true, false),
        ]);
        $validator = new PrimitiveTypeValidator($metadata);
        $value = 'neither_int_nor_bool';

        $this->expectException(TypeError::class);
        $validator->validate($value);
    }

    /**
     * プロパティメタデータインスタンスを作成
     *
     * @param array<int, TypeHint> $typeHints
     */
    private function createPropertyMetadata(array $typeHints = []): PropertyMetadata
    {
        return new PropertyMetadata(
            'TestClass',
            'testProperty',
            $typeHints,
            PropertyInitializedStatus::BY_DEFAULT,
        );
    }
}
