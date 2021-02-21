<?php


namespace App\DataPersister;


use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use App\Entity\Account;

class AccountDataPersister implements ContextAwareDataPersisterInterface
{
    private $decoratedDataPersister;
    private $userPasswordEncoder;
    private $security;

    public function __construct(DataPersisterInterface $decoratedDataPersister, UserPasswordEncoderInterface $userPasswordEncoder, Security $security)
    {
        $this->decoratedDataPersister = $decoratedDataPersister;
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->security = $security;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Account;
    }

    /**
     * @param Account $account
     */
    public function persist($account, array $context = [])
    {
        if ($account->getPlainPassword()) {
            $account->setPassword(
                $this->userPasswordEncoder->encodePassword($account, $account->getPlainPassword())
            );
            $account->eraseCredentials();
        }

        return $this->decoratedDataPersister->persist($account);
    }

    public function remove($data, array $context = [])
    {
        $this->decoratedDataPersister->remove($data);
    }
}