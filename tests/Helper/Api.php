<?php


namespace App\Tests\Helper;


use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use App\Entity\Account;
use App\Tests\Factory\AccountFactory;
use Symfony\Component\HttpFoundation\Response;

class Api
{
    const PREFIX = '/api';
    const ACCOUNT = self::PREFIX . '/accounts';
    const TRIP = self::PREFIX . '/trips';
    const COUNTRY = self::PREFIX . '/countries';

    const DATE_FORMAT = 'Y-m-d';

    const CODE_VALIDATION = Response::HTTP_UNPROCESSABLE_ENTITY;

    public static function logIn(Client $client, Account $account, $password = AccountFactory::DEFAULT_PASSWORD)
    {
        $browser = $client->getKernelBrowser();
        $browser->setServerParameter('PHP_AUTH_USER', $account->getEmail());
        $browser->setServerParameter('PHP_AUTH_PW', $password);

        return $client;
    }
}