<?php

declare(strict_types=1);

namespace LM\WebFramework\Validation;

use InvalidArgumentException;
use LM\WebFramework\Model\Type\ArrayModel;
use LM\WebFramework\Validation\ConstraintViolation\ConstraintViolation;
use LM\WebFramework\Model\Type\IModel;
use LM\WebFramework\Model\Type\BoolModel;
use LM\WebFramework\Model\Type\DataArrayModel;
use LM\WebFramework\Model\Type\DateTimeModel;
use LM\WebFramework\Model\Type\EntityListModel;
use LM\WebFramework\Model\Type\EntityModel;
use LM\WebFramework\Model\Type\ForeignEntityModel;
use LM\WebFramework\Model\Type\IntModel;
use LM\WebFramework\Model\Type\JsonModel;
use LM\WebFramework\Model\Type\ListModel;
use LM\WebFramework\Model\Type\StringModel;

/**
 * Validator for app data.
 * 
 * This class is instanciated with a model. It can then validate that
 * the app data it is passed conforms to the model or not, and what
 * the violations are.
 * 
 * @todo Rename ValidatorFactory or to ArrayValidator or to EntityValidator?
 */
final class Validator
{
    private ITypeValidator $validator;

    public function __construct(
        private IModel $model,
    ) {
        switch ($model::class) {
            case ForeignEntityModel::class:
                $this->validator = new ForeignEntityValidator($model);
                break;

            case DataArrayModel::class:
            case EntityModel::class:
                $this->validator = new EntityValidator($model);
                break;

            case BoolModel::class:
                $this->validator = new BoolValidator($model);
                break;

            case DateTimeModel::class:
                $this->validator = new DateTimeValidator($model);
                break;

            case IntModel::class:
                $this->validator = new IntValidator($model);
                break;

            case EntityListModel::class:
            case ListModel::class:
                $this->validator = new ListValidator($model);
                break;

            case StringModel::class:
                $this->validator = new StringValidator($model);
                break;
            
            case JsonModel::class:
                $this->validator = new JsonValidator();
                break;

            default:
                throw new InvalidArgumentException('Model not supported.');
        }
    }

    /**
     * Check that the given app datum satisfies the constraints specified in the
     * model.
     *
     * @param mixed $value The app value to validate.
     * @return ConstraintViolation[] A list of ConstraintViolations, one for
     * each constraint violation.
     */
    public function validate(mixed $value): array
    {
        $violations = [];

        if (null === $value) {
            if (!$this->model->isNullable()) {
                $violations = [
                    new ConstraintViolation(
                        $this->model->getNotNullConstraint(),
                        'Data is not allowed to be null.',
                    ),
                ];
            }
        } else {
            $violations = $this->validator->validate($value);
        }

        return $violations;
    }
}
