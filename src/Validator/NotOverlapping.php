<?php


namespace App\Validator;


use Symfony\Component\Validator\Constraint;


/**
 * @Annotation
 */
class NotOverlapping extends Constraint
{
    public $message = 'This value overlaps with other values.';
    public $service = 'App\Validator\NotOverlappingValidator';
    public $startedAt = 'startedAt';
    public $finishedAt = 'finishedAt';
    public $fields = [];
    public $entityClass = null;
    public $errorPath = null;
    public $identifierFieldNames = [];

    /**
     * The validator must be defined as a service with this name.
     *
     * @return string
     */
    public function validatedBy()
    {
        return $this->service;
    }

    /**
     * @return string
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}