<?php

namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use Codeception\Module\Symfony;

class Api extends \Codeception\Module
{
    const PREFIX = '/trip';


    const CODE_OK = 200;
    const CODE_VALIDATION = 400;

    const INVALID_EMAIL = 'user1';
    const USER_FIRST = 'user1@example.com';
    const PASSWORD_FIRST = 'password1';
    const USER_SECOND = 'user2@example.com';
    const PASSWORD_SECOND = 'password2';

    public function jsonRequest()
    {
        /** @var Symfony $symfony */
        $symfony =  $this->getModule('Symfony');
        $symfony->haveHttpHeader('Content-Type', 'application/json');
        $symfony->haveHttpHeader('Accept', 'application/json');
    }
}
