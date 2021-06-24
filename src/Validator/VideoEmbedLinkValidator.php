<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class VideoEmbedLinkValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\VideoEmbedLink */
        if (null === $value || '' === $value) {
            return;
        }

        if(preg_match('@^(https\:\/\/www\.youtube\.com\/embed\/)([0-9A-Za-z\-\*\_\.]+)@', $value)) {
            return;
        }

        // TODO: implement the validation here
        $this->context->buildViolation($constraint->message)
            ->setParameter('XXXXXX', $value)
            ->addViolation();
    }
}
