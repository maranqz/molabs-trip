<?php


namespace TripBundle\Api;


use Symfony\Component\HttpFoundation\Response;
use TripBundle\Entity\Account as Entity;
use TripBundle\Manipulator\AccountManipulatorInterface;
use TripBundle\Model\AccountUpdate;
use TripBundle\Model\Account;
use Symfony\Component\Validator\Constraints as Assert;
use TripBundle\Service\ValidatorInterface;

class AccountsApi implements AccountsApiInterface
{
    private AccountManipulatorInterface $manipulator;
    private $validator;

    public function __construct(AccountManipulatorInterface $manipulator)
    {
        $this->manipulator = $manipulator;
    }

    public function setValidator(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function setBearerAuth($value)
    {
        // TODO: Implement setBearerAuth() method.
    }

    public function createAccount(AccountUpdate $dto, &$responseCode, array &$responseHeaders)
    {
        $account = new Entity();
        $account->setEmail($dto->getEmail());
        $account->setPassword($dto->getPassword());

        $this->manipulator->register($account);

        return Account::fromEntity($account);
    }

    public function deleteAccount($accountId, &$responseCode, array &$responseHeaders)
    {
        $account = $this->manipulator->byIdOrThrowException($accountId);

        return $this->manipulator->delete($account);
    }

    public function getAccount($accountId, &$responseCode, array &$responseHeaders)
    {
        $entity = $this->manipulator->byIdOrThrowException($accountId);

        $dto = new Account();
        $dto->setEmail($entity->getEmail())
            ->setId($entity->getId());
        return $dto;
    }

    public function updateAccount($accountId, AccountUpdate $dto, &$responseCode, array &$responseHeaders)
    {
        $updatableAccount = $this->manipulator->byIdOrThrowException($accountId);

        $updatableAccount->setEmail($dto->getEmail());
        $updatableAccount->setPassword($dto->getPassword());

        return $this->manipulator->update($updatableAccount);
    }

    private function validate(Entity $account)
    {
        return $this->validator->validate($account);
    }
}