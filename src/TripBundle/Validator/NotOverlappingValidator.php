<?php


namespace TripBundle\Validator;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NotOverlappingValidator extends ConstraintValidator
{
    use DTOValidatorTrait;

    /**
     * @var ManagerRegistry
     */
    private $registry;

    /**
     * NotOverlappingValidator constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @param object $object
     * @param Constraint $constraint
     *
     * @throws UnexpectedTypeException
     * @throws ConstraintDefinitionException
     */
    public function validate($object, Constraint $constraint)
    {
        if (!$constraint instanceof NotOverlapping) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__ . '\NotOverlapping');
        }

        if (!\is_array($constraint->fields) && !\is_string($constraint->fields)) {
            throw new UnexpectedTypeException($constraint->fields, 'array');
        }

        $fields = (array)$constraint->fields;

        if (!is_null($constraint->errorPath) && !is_string($constraint->errorPath)) {
            throw new UnexpectedTypeException($constraint->errorPath, 'string or null');
        }

        if (empty($constraint->startedAt) || empty($constraint->finishedAt)) {
            throw new ConstraintDefinitionException('startedAt and finishedAt should be set.');
        }

        if (is_null($object)) {
            return;
        }

        $entityClass = \get_class($object);
        $objectIsEntity = true;
        if (isset($constraint->entityClass)) {
            $entityClass = $constraint->entityClass;
            $objectIsEntity = false;
        }

        $em = $this->registry->getManagerForClass($entityClass);

        if (!$em) {
            throw new ConstraintDefinitionException(
                sprintf('Unable to find the object manager associated with an entity of class "%s".',
                    $entityClass
                ));
        }

        $class = $em->getClassMetadata($entityClass);

        $startedAtValue = $this->getFieldValue($class, $constraint->startedAt, $object);
        $finishedAtValue = $this->getFieldValue($class, $constraint->finishedAt, $object);

        if (empty($startedAtValue) || empty($finishedAtValue)) {
            throw new \InvalidArgumentException(sprintf('"%s" and "%s" should be set', $constraint->startedAt,
                $constraint->finishedAt));
        }

        $criteria = new Criteria();
        $fieldsCriteria = [];
        foreach ($fields as $field) {
            $fieldsCriteria[] = $criteria->expr()->eq(
                $field,
                $this->getFieldValue($class, $field, $object)
            );
        }
        if (!empty($fieldsCriteria)) {
            $criteria->andWhere($criteria->expr()->andX(...$fieldsCriteria));
        }

        $criteria->andWhere(
            $criteria->expr()->orX(
                $criteria->expr()->andX(
                    $criteria->expr()->lte($constraint->startedAt, $startedAtValue),
                    $criteria->expr()->gte($constraint->finishedAt, $startedAtValue),
                ),
                $criteria->expr()->andX(
                    $criteria->expr()->lte($constraint->startedAt, $finishedAtValue),
                    $criteria->expr()->gte($constraint->finishedAt, $finishedAtValue),
                ),
                $criteria->expr()->andX(
                    $criteria->expr()->gte($constraint->startedAt, $startedAtValue),
                    $criteria->expr()->lte($constraint->finishedAt, $finishedAtValue),
                ),
            ))
            ->setMaxResults(2);

        $repository = $em->getRepository($entityClass);
        $result = $repository->matching($criteria);

        if ($result instanceof \IteratorAggregate) {
            $result = $result->getIterator();
        }

        /* If the result is a MongoCursor, it must be advanced to the first
         * element. Rewinding should have no ill effect if $result is another
         * iterator implementation.
         */
        if ($result instanceof \Iterator) {
            $result->rewind();
            if ($result instanceof \Countable && 1 < \count($result)) {
                $result = [$result->current(), $result->current()];
            } else {
                $result = $result->current();
                $result = null === $result ? [] : [$result];
            }
        } elseif (\is_array($result)) {
            reset($result);
        } else {
            $result = null === $result ? [] : [$result];
        }

        /* If no entity matched the query criteria or a single entity matched,
         * which is the same as the entity being validated, the criteria is
         * unique.
         */
        if (!$result || (1 === \count($result) && current($result) === $object)) {
            return;
        }

        /* If a single entity matched the query criteria, which is the same as
         * the entity being updated by validated object, the criteria is unique.
         */
        if (!$objectIsEntity && !empty($constraint->identifierFieldNames) && 1 === \count($result)) {
            if (!\is_array($constraint->identifierFieldNames) && !\is_string($constraint->identifierFieldNames)) {
                throw new UnexpectedTypeException($constraint->identifierFieldNames, 'array');
            }

            $identifierFieldNames = (array)$constraint->identifierFieldNames;

            $fieldValues = $this->getFieldValues($object, $class, $identifierFieldNames);
            if (array_values($class->getIdentifierFieldNames()) != array_values($identifierFieldNames)) {
                throw new ConstraintDefinitionException(sprintf('The "%s" entity identifier field names should be "%s", not "%s".',
                    $entityClass, implode(', ', $class->getIdentifierFieldNames()),
                    implode(', ', $constraint->identifierFieldNames)));
            }

            $entityMatched = true;

            foreach ($identifierFieldNames as $identifierFieldName) {
                $field = new \ReflectionProperty($entityClass, $identifierFieldName);
                if (!$field->isPublic()) {
                    $field->setAccessible(true);
                }

                $propertyValue = $this->getPropertyValue($entityClass, $identifierFieldName, current($result));
                if ($fieldValues[$identifierFieldName] !== $propertyValue) {
                    $entityMatched = false;
                    break;
                }
            }

            if ($entityMatched) {
                return;
            }
        }

        $errorPath = null !== $constraint->errorPath ? $constraint->errorPath : $constraint->startedAt;

        $this->context->buildViolation($constraint->message)
            ->atPath($errorPath)
            ->addViolation();
    }

    protected function getFieldValue($class, $fieldName, $object)
    {
        if (!$class->hasField($fieldName) && !$class->hasAssociation($fieldName)) {
            throw new ConstraintDefinitionException(sprintf('The field "%s" is not mapped by Doctrine, so it cannot be validated for uniqueness.',
                $fieldName));
        }

        return $this->getPropertyValue(get_class($object), $fieldName, $object);
    }
}