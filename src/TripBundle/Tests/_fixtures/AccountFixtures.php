<?php


namespace _fixtures;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Helper\Api;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use TripBundle\Entity\Account;

class AccountFixtures extends Fixture
{
    use FackerTrait;

    const DEFAULT_PASSWORD = API::PASSWORD_FIRST;

    private $encoder;
    private $dynamicPassword;

    public function __construct(UserPasswordEncoderInterface $encoder, $dynamicPassword = true)
    {
        $this->encoder = $encoder;
        $this->dynamicPassword = $dynamicPassword;
    }

    public function load(ObjectManager $manager)
    {
        $account = new Account();

        $plaintPassword = static::DEFAULT_PASSWORD;
        if ($this->dynamicPassword) {
            $plaintPassword = $this->getFacker()->password;
        }

        $account->setEmail($this->getFacker()->email)
            ->setPlainPassword($plaintPassword);

        $account->setPassword(
            $this->encoder->encodePassword($account, $account->getPlainPassword())
        );

        $manager->persist($account);
        $manager->flush();
    }
}