<?php


namespace TripBundle\Manipulator;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use TripBundle\Entity\Account;

class AccountManipulator implements AccountManipulatorInterface
{
    private EntityManagerInterface $em;
    private ObjectRepository $repository;
    private EncoderFactoryInterface $encoderFactory;

    public function __construct(EntityManagerInterface $manager, EncoderFactoryInterface $encoderFactory)
    {
        $this->em = $manager;
        $this->repository = $manager->getRepository(Account::class);
        $this->encoderFactory = $encoderFactory;
    }

    public function register(Account $account): Account
    {
        $this->preparePassword($account);

        $this->em->persist($account);
        $this->em->flush();

        return $account;
    }

    public function update(Account $account): Account
    {
        $this->preparePassword($account);
        $this->em->flush();

        return $account;
    }

    public function delete(Account $account): bool
    {
        $this->em->remove($account);
        $this->em->flush();

        return true;
    }

    public function byIdOrThrowException(int $accountId): Account
    {
        /** @var Account $account */
        $account = $this->repository->find($accountId);

        if (!$account) {
            throw new \InvalidArgumentException(sprintf('Account identified by "%s" id does not exist.', $accountId));
        }

        return $account;
    }

    private function preparePassword(Account $account)
    {
        if (!empty($account->getPlainPassword())) {
            $account->setPassword(
                $this->encoderFactory->getEncoder($account)->encodePassword(
                    $account->getPlainPassword(), null
                )
            );
        }
    }
}