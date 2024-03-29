<?php

namespace LM\WebFramework\Validator;

use LM\WebFramework\Constraints\EnumConstraint;
use LM\WebFramework\DataStructures\ConstraintViolation;

class EnumValidator implements IValidator
{
    public function __construct(
        private EnumConstraint $constraint,
    ) {
    }

    public function validate(mixed $data): array {
        if (!in_array($data, $this->constraint->getValues(), true)) {
            return [
                new ConstraintViolation($this->constraint, 'Data does not adhere to the list of permitted values.'),
            ];
        }
        return [];
    }
}