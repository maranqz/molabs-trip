<?php


namespace TripBundle\Api;


use TripBundle\Manipulator\AccountManipulatorInterface;
use TripBundle\Model\Account;

class AccountsApi implements AccountsApiInterface
{
    private AccountManipulatorInterface $manipulator;

    public function __construct(AccountManipulatorInterface $manipulator)
    {
        $this->manipulator = $manipulator;
    }

    public function createAccount(Account $account, &$responseCode, array &$responseHeaders)
    {
        return $this->manipulator->register($account);
    }

    public function deleteAccount($accountId, &$responseCode, array &$responseHeaders)
    {
        $account = $this->manipulator->byIdOrThrowException($accountId);

        return $this->manipulator->delete($account);
    }

    public function getAccount($accountId, &$responseCode, array &$responseHeaders)
    {
        return $this->manipulator->byIdOrThrowException($accountId);
    }

    public function updateAccount($accountId, Account $account, &$responseCode, array &$responseHeaders)
    {
        $updatableAccount = $this->manipulator->byIdOrThrowException($accountId);

        $updatableAccount->setEmail($account->getEmail());
        $updatableAccount->setPassword($account->getPassword());

        return $this->manipulator->update($account);
    }

}