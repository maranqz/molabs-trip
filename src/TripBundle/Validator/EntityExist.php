<?php

declare(strict_types=1);

namespace TripBundle\Validator;

use Symfony\Component\Validator\Constraint;


/**
 * @Annotation
 */
class EntityExist extends Constraint
{
    public $message = 'Entity "%entity%" does not exist.';
    public $entityClass;
}
