<?php

declare(strict_types=1);

namespace PhSculptis\Test\Fields;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use PhSculptis\Fields\DecimalField;
use PhSculptis\Core\Validators\DecimalValidator;
use PhSculptis\Core\Definitions\DecimalValidatorDefinition;

class DecimalFieldValidateTestClass
{
    public float $prop;
}

/**
 * DecimalFieldのテストクラス
 *
 * DecimalFieldクラスのgetValidatorメソッドが適切に動作することを確認するためのテスト。
 * getValidatorメソッドはDecimalValidatorインスタンスを返し、これを使用して小数値の検証を行えることを確認します。
 */
class DecimalFieldTest extends TestCase
{
    /**
     * デフォルト設定でのgetValidatorメソッドの動作をテスト
     *
     * 検証内容:
     * - DecimalFieldのgetValidatorメソッドがDecimalValidatorクラスの名前（文字列）を返すこと
     */
    #[Test]
    public function testGetValidatorReturnsDecimalValidator(): void
    {
        $field = new DecimalField();
        $validator = $field->getValidator();

        $this->assertEquals(DecimalValidator::class, $validator);
    }


    /**
     * getDefinitionメソッドが適切なDecimalValidatorDefinitionを返すことをテスト
     *
     * 検証内容:
     * - DecimalFieldのgetDefinitionメソッドが適切なDecimalValidatorDefinitionオブジェクトを返すこと
     */
    #[Test]
    public function testGetDefinitionReturnsDecimalValidatorDefinition(): void
    {
        $field = new DecimalField(maxDigits: 5, decimalPlaces: 2, gt: 0, lt: 100);
        $definition = $field->getDefinition();

        $this->assertInstanceOf(DecimalValidatorDefinition::class, $definition);
        $this->assertEquals(5, $definition->maxDigits);
        $this->assertEquals(2, $definition->decimalPlaces);
        $this->assertEquals(0, $definition->gt);
        $this->assertEquals(100, $definition->lt);
        $this->assertNull($definition->ge);
        $this->assertNull($definition->le);
    }
}
