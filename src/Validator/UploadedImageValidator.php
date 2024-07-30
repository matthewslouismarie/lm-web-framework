<?php

declare(strict_types=1);

namespace LM\WebFramework\Validator;

use LM\WebFramework\Model\Constraints\IUploadedImageConstraint;
use LM\WebFramework\DataStructures\ConstraintViolation;

final class UploadedImageValidator implements ITypeValidator
{
    public function __construct(
        private IUploadedImageConstraint $constraint,
    ) {
    }

    /**
     * @todo Refactor.
     * @todo Check that the image does not already exist.
     */
    public function validate(mixed $data): array {
        $violations = [];
        if (is_array($data)) {

        }
        else if ($data == IUploadedImageConstraint::FILE_TOO_BIG) {
            $violations[] = new ConstraintViolation($this->constraint, 'Le fichier est trop gros.');
        }
        else if ($data == IUploadedImageConstraint::MISC_ERROR) {
            $violations[] = new ConstraintViolation($this->constraint, 'Il y a un problème avec le fichier.');
        }
        else {
            if (strlen($data) > IUploadedImageConstraint::FILENAME_MAX_LENGTH) {
                $violations[] = new ConstraintViolation($this->constraint, 'Le nom du fichier est trop long.');
            }
            
            if (1 !== preg_match('/' . IUploadedImageConstraint::FILENAME_REGEX . '/', $data)) {
                $violations[] = new ConstraintViolation($this->constraint, 'Le nom du fichier n’a pas le bon format.');
            }
        }
        
        return $violations;
    }
}