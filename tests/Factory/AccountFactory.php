<?php

namespace App\Tests\Factory;

use App\Entity\Account;
use App\Repository\AccountRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @method static        Account|Proxy createOne(array $attributes = [])
 * @method static        Account[]|Proxy[] createMany(int $number, $attributes = [])
 * @method static        Account|Proxy findOrCreate(array $attributes)
 * @method static        Account|Proxy random(array $attributes = [])
 * @method static        Account|Proxy randomOrCreate(array $attributes = [])
 * @method static        Account[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static        Account[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static        AccountRepository|RepositoryProxy repository()
 * @method Account|Proxy create($attributes = [])
 */
final class AccountFactory extends ModelFactory
{
    const USER_FIRST = 'user1@example.com';
    const USER_SECOND = 'user2@example.com';
    const INVALID_EMAIL = 'user1';

    const DEFAULT_PASSWORD = 'test';

    protected function getDefaults(): array
    {
        return [
            'email' => self::faker()->email,
            'username' => self::faker()->userName,
            // hashed version of "test"
            // php bin/console security:encode-password --env=test
            'password' => '$argon2id$v=19$m=10,t=3,p=1$eyXPWiQFWUO901E78Bb3UQ$hyu9dFDz7fo2opQyCSoX/NfJDvEpzER/a+WbiAagqqw',
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this// ->afterInstantiate(function(Account $account) {})
            ;
    }

    protected static function getClass(): string
    {
        return Account::class;
    }
}
