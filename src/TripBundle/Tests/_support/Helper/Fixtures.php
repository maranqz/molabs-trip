<?php


namespace TripBundle\Tests\Helper;


use _fixtures\FackerTrait;
use Codeception\Module\Doctrine2;
use Codeception\Module\Symfony;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use TripBundle\Entity\Account;
use TripBundle\Entity\Country;
use TripBundle\Entity\Trip;

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

    public function getTrip($args = []): Trip
    {
        $class = Trip::class;

        $args['createdBy'] = $args['createdBy'] ?? $this->getAccount();
        $args['country'] = $args['country'] ?? $this->getCountry();
        $args['startedAt'] = $args['startedAt'] ?? $this->getFacker()->dateTime;
        $args['finishedAt'] = $args['finishedAt'] ?? (clone $args['startedAt'])->add(new \DateInterval('P1D'));
        $args['notes'] = $args['notes'] ?? substr($this->getFacker()->randomAscii, 0, 255);

        $tripId = $this->doctrine()->haveInRepository($class, $args);
        /** @var Trip $entity */
        $entity = $this->doctrine()->grabEntityFromRepository($class, ['id' => $tripId]);

        $entity->setCreatedBy($args['createdBy']);

        return $entity;
    }

    public function getCountry($args = []): Country
    {
        $class = Country::class;

        $args['code'] = $args['code'] ?? $this->getFacker()->countryISOAlpha3;
        $args['name'] = $args['name'] ?? $this->getFacker()->country;
        $args['region'] = $args['region'] ?? substr($this->getFacker()->randomAscii, 0, 10);

        $countryCode = $this->doctrine()->haveInRepository($class, $args);
        /** @var Country $country */
        $country = $this->doctrine()->grabEntityFromRepository($class, ['code' => $countryCode]);

        return $country;
    }
}