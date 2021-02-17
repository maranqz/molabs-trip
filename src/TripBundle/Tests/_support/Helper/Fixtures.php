<?php


namespace Helper;


use _fixtures\AccountFixtures;
use _fixtures\FackerTrait;
use Codeception\Module\Doctrine2;
use Codeception\Module\Symfony;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use TripBundle\Entity\Account;

class Fixtures extends \Codeception\Module
{
    use FackerTrait;

    protected function doctrine(): Doctrine2
    {
        return $this->getModule('Doctrine2');
    }

    protected function symfony(): Symfony
    {
        return $this->getModule('Symfony');
    }

    public function getAccount($args = []): Account
    {
        /** @var Account $account */
        $account = new Account();
        /** @var UserPasswordEncoderInterface $encoder */
        $encoder = $this->symfony()->grabService(UserPasswordEncoderInterface::class);

        $args['email'] = $args['email'] ?? $this->getFacker()->email;

        $plainPassword = $args['password'] ?? $this->getFacker()->password;
        $args['password'] = $encoder->encodePassword($account, $plainPassword);

        $accountId = $this->doctrine()->haveInRepository($account, $args);
        $account = $this->doctrine()->grabEntityFromRepository(Account::class, ['id' => $accountId]);

        $account->setPlainPassword($plainPassword);

        return $account;
    }
}