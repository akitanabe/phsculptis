<?php

declare(strict_types=1);

namespace PhSculptis\Test\Validators;

use PhSculptis\Validators\WrapValidator;
use PhSculptis\Validators\ValidatorMode;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * WrapValidatorのテストクラス
 *
 * WrapValidatorは FunctionalValidator を継承し、
 * WRAP モードと callable を提供する
 */
#[CoversClass(WrapValidator::class)]
class WrapValidatorTest extends TestCase
{
    /**
     * getModeがValidatorMode::WRAPを返すことを確認
     */
    #[Test]
    public function shouldReturnCorrectMode(): void
    {
        // Arrange
        $callable = fn($value, $handler) => $handler($value);
        $validator = new WrapValidator($callable);

        // Act
        $mode = $validator->getMode();

        // Assert
        $this->assertSame(ValidatorMode::WRAP, $mode);
    }

    /**
     * getCallableがコンストラクタで渡されたcallableを返すことを確認
     */
    #[Test]
    public function shouldReturnCallablePassedInConstructor(): void
    {
        // Arrange
        $callable = fn($value, $handler) => $handler($value . '_wrap');
        $validator = new WrapValidator($callable);

        // Act
        $returnedCallable = $validator->resolveValidator();

        // Assert
        $this->assertSame($callable, $returnedCallable);
        // 実際に呼び出して確認 (ハンドラーをモックまたは単純な関数で代用)
        $mockHandler = fn($v) => $v . '_handled';
        $this->assertEquals('test_wrap_handled', $returnedCallable('test', $mockHandler));
    }
}
