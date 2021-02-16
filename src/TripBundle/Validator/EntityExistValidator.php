<?php

declare(strict_types=1);

namespace TripBundle\Validator;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;


class EntityExistValidator extends ConstraintValidator
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function validate($entity, Constraint $constraint): void
    {
        if (empty($entity)) {
            return;
        }

        if (!$constraint instanceof EntityExist) {
            throw new \LogicException(\sprintf('You can only pass %s constraint to this validator.', EntityExist::class));
        }

        if (empty($constraint->entityClass)) {
            throw new \LogicException(\sprintf('Must set "entity" on "%s" validator', EntityExist::class));
        }

        if (!$this->entityManager->contains($entity)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('%entity%', $constraint->entityClass)
                ->setParameter('%value%', $entity)
                ->addViolation();
        }
    }
}
