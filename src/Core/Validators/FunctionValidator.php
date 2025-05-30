<?php

declare(strict_types=1);

namespace PhSculptis\Core\Validators;

use Closure;
use LogicException;
use PhSculptis\Core\Definitions\FunctionValidatorDefinition;
use PhSculptis\Core\ValidatorDefinitions;
use PhSculptis\Validators\ValidatorFunctionWrapHandler;
use PhSculptis\Validators\ValidatorMode;

/**
 * ユーザー入力バリデーション処理の基底クラス
 */
abstract class FunctionValidator implements Validatorable
{
    public function __construct(
        protected readonly Closure $validator,
    ) {}

    /**
     * バリデーション処理を実行する
     * 具象クラスで実装する
     */
    abstract public function validate(mixed $value, ?ValidatorFunctionWrapHandler $handler = null): mixed;

    public static function build(ValidatorDefinitions $definitions): Validatorable
    {
        $functionalValidatorQueue = $definitions->get(FunctionValidatorDefinition::class);

        if ($functionalValidatorQueue === null) {
            throw new LogicException('FunctionalValidatorQueueDefinition is not set.');
        }

        $validatorCallable = $functionalValidatorQueue->dequeue();

        /**
         * @var class-string<FunctionValidator> $funtionValidatorClass
         */
        $funtionValidatorClass = match ($validatorCallable->getMode()) {
            ValidatorMode::BEFORE => FunctionBeforeValidator::class,
            ValidatorMode::AFTER => FunctionAfterValidator::class,
            ValidatorMode::WRAP => FunctionWrapValidator::class,
            ValidatorMode::PLAIN => FunctionPlainValidator::class,
        };

        $validator = $validatorCallable->resolveValidator();

        return new $funtionValidatorClass($validator);
    }
}
