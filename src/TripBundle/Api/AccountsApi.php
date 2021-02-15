<?php


namespace TripBundle\Api;


use TripBundle\Entity\Account as Entity;
use TripBundle\Manipulator\AccountManipulatorInterface;
use TripBundle\Model\AccountCreate;
use TripBundle\Model\AccountUpdate;
use TripBundle\Model\Account;

class AccountsApi implements AccountsApiInterface
{
    private AccountManipulatorInterface $manipulator;

    public function __construct(AccountManipulatorInterface $manipulator)
    {
        $this->manipulator = $manipulator;
    }

    public function setBearerAuth($value)
    {
        // TODO: Implement setBearerAuth() method.
    }

    public function createAccount(AccountCreate $dto, &$responseCode, array &$responseHeaders)
    {
        $account = new Entity();
        $account->setEmail($dto->getEmail());
        $account->setPlainPassword($dto->getPassword());

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
        $account = $this->manipulator->byIdOrThrowException($accountId);

        return Account::fromEntity($account);
    }

    public function updateAccount($accountId, AccountUpdate $dto, &$responseCode, array &$responseHeaders)
    {
        $updatableAccount = $this->manipulator->byIdOrThrowException($accountId);

        if (!empty($dto->getEmail())) {
            $updatableAccount->setEmail($dto->getEmail());
        }
        if (!empty($dto->getPassword())) {
            $updatableAccount->setPlainPassword($dto->getPassword());
        }

        $this->manipulator->update($updatableAccount);
        return Account::fromEntity($updatableAccount);
    }
}