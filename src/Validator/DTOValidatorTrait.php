<?php

namespace App\Validator;

use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;

trait DTOValidatorTrait
{
    protected function getFieldValues($object, ClassMetadata $class, array $fields, bool $isEntity = false): array
    {
        if (!$isEntity) {
            $reflectionObject = new \ReflectionObject($object);
        }

        $fieldValues = [];
        $objectClass = \get_class($object);

        foreach ($fields as $objectFieldName => $entityFieldName) {
            if (!$class->hasField($entityFieldName) && !$class->hasAssociation($entityFieldName)) {
                throw new ConstraintDefinitionException(sprintf('The field "%s" is not mapped by Doctrine, so it cannot be validated for uniqueness.', $entityFieldName));
            }

            $fieldName = \is_int($objectFieldName) ? $entityFieldName : $objectFieldName;
            if (!$isEntity) {
                if (!$reflectionObject->hasProperty($fieldName)) {
                    throw new ConstraintDefinitionException(sprintf('The field "%s" is not a property of class "%s".', $fieldName, $objectClass));
                }
            }

            $fieldValues[$entityFieldName] = $this->getPropertyValue($objectClass, $fieldName, $object);
        }

        return $fieldValues;
    }

    protected function getPropertyValue($class, $name, $object)
    {
        $property = new \ReflectionProperty($class, $name);
        if (!$property->isPublic()) {
            $property->setAccessible(true);
        }

        return $property->getValue($object);
    }
}
