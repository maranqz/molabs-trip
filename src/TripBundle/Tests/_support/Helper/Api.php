<?php

namespace TripBundle\Tests\Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use Codeception\Module\Symfony;
use Codeception\Util\HttpCode;

class Api extends \Codeception\Module
{
    const PREFIX = '/trip';
    const ACCOUNT = self::PREFIX . '/accounts/';
    const TRIP = self::PREFIX .'/trips/';

    const DATE_FORMAT = 'Y-m-d';

    const CODE_VALIDATION = HttpCode::BAD_REQUEST;

    const INVALID_EMAIL = 'user1';
    const USER_FIRST = 'user1@example.com';
    const PASSWORD_FIRST = 'password1';
    const USER_SECOND = 'user2@example.com';
    const PASSWORD_SECOND = 'password2';

    public function jsonRequest()
    {
        /** @var Symfony $symfony */
        $symfony = $this->getModule('Symfony');
        $symfony->haveHttpHeader('Content-Type', 'application/json');
        $symfony->haveHttpHeader('Accept', 'application/json');
    }
}
