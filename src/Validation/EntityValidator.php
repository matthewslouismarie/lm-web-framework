<?php

declare(strict_types=1);

namespace LM\WebFramework\Validation;

use LM\WebFramework\Validation\ConstraintViolation\ConstraintViolation;
use LM\WebFramework\Model\Type\EntityModel;

final class EntityValidator implements ITypeValidator
{
    public function __construct(
        private EntityModel $model,
    ) {
    }

    public function validate(mixed $value): array
    {
        if (!is_array($value)) {
            return [
                new ConstraintViolation($this->model, 'Value must be an associative array.'),
            ];
        }

        $violations = [];
        foreach ($this->model->getProperties() as $key => $model) {
            if (key_exists($key, $value)) {
                $propertyViolations = (new Validator($model))->validate($value[$key]);
            } else {
                $propertyViolations = [
                    new ConstraintViolation($this->model, "Property {$key} is not defined."),
                ];
            }
            if (count($propertyViolations) > 0) {
                $violations[$key] = $propertyViolations;
            }
        }

        return $violations;
    }
}
