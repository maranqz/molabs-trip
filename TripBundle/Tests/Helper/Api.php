<?php

namespace TripBundle\Tests\Helper;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use Symfony\Component\HttpFoundation\Response;
use TripBundle\Entity\Account;
use TripBundle\Tests\Factory\AccountFactory;

class Api
{
    const PREFIX = '/api';
    const ACCOUNT = self::PREFIX.'/accounts';
    const TRIP = self::PREFIX.'/trips';
    const COUNTRY = self::PREFIX.'/countries';

    const DATE_FORMAT = 'Y-m-d';

    const CODE_VALIDATION = Response::HTTP_UNPROCESSABLE_ENTITY;

    public static function logIn(Client $client, Account $account, $password = AccountFactory::DEFAULT_PASSWORD)
    {
        $browser = $client->getKernelBrowser();
        $browser->setServerParameter('PHP_AUTH_USER', $account->getUsername());
        $browser->setServerParameter('PHP_AUTH_PW', $password);

        return $client;
    }
}
