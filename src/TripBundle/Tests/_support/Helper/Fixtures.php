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
        $this->reopenEntityManager();

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
        $this->reopenEntityManager();

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
        $this->reopenEntityManager();

        $class = Country::class;

        $args['code'] = $args['code'] ?? $this->getFacker()->asciify('***');
        $args['name'] = $args['name'] ?? $this->getFacker()->country;
        $args['region'] = $args['region'] ?? $this->getFacker()->regexify('[A-Z]{10}');

        $countryCode = $this->doctrine()->haveInRepository($class, $args);
        /** @var Country $country */
        $country = $this->doctrine()->grabEntityFromRepository($class, ['code' => $countryCode]);

        return $country;
    }

    /**
     * TODO remove after https://github.com/Codeception/module-doctrine2/issues/35
     */
    public function reopenEntityManager()
    {
        $doctrineModule = $this->doctrine();

        // If the em is fine then there is nothing to do
        if ($doctrineModule->em->isOpen()) {
            return;
        }

        $symfony = $this->symfony();
        $container = $symfony->_getContainer();

        // Get a new EM
        $em = $container->get('doctrine')->resetManager();
        // Set it in the Symfony module container
        $container->set('doctrine.orm.app_entity_manager', $em);
        // Ensure the symfony module has the new EM
        $symfony->persistService('doctrine.orm.app_entity_manager');

        // And ensure that the doctrine module has the new EM
        $doctrineModule->em = $em;
    }
}