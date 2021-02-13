<?php


namespace TripBundle\Manipulator;


use TripBundle\Model\Account;

interface AccountManipulatorInterface
{
    public function register(Account $account): Account;

    public function update(Account $account): Account;

    public function delete(Account $account): bool;

    public function byIdOrThrowException(int $accountId): Account;
}