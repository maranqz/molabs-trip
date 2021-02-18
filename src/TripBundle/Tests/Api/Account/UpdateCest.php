<?php

namespace Api\Account;

use Codeception\Util\HttpCode;
use TripBundle\Tests\Helper\Api;
use Codeception\Example;
use TripBundle\Tests\Helper\Validator;
use TripBundle\Tests\ApiTester;

class UpdateCest
{
    public function _before(ApiTester $I)
    {
        $I->jsonRequest();
    }

    public function updateUnAuthorized(ApiTester $I)
    {
        $account = $I->getAccount();

        $I->sendPatch(sprintf(API::ACCOUNT . '%s', $account->getId()), [
            'email' => Api::USER_SECOND,
        ]);

        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    /**
     * @dataProvider updateAccountProvider
     */
    public function update(ApiTester $I, Example $test)
    {
        $account = $I->getAccount();
        $I->amHttpAuthenticated($account->getEmail(), $account->getPlainPassword());

        $new = $test['new'];
        if (($new['email'] ?? false) === true) {
            $new['email'] = $account->getEmail();
        }

        $I->sendPatch(sprintf(API::ACCOUNT . '%s', $account->getId()), $new);

        $I->seeResponseCodeIs($test['code']);
        $I->seeResponseIsJson();

        $expected = $test['expected'];
        if ($expected['id'] ?? false) {
            $expected['id'] = $account->getId();
        }
        if ($expected['email'] === true) {
            $expected['email'] = $account->getEmail();
        }

        $I->seeResponseContainsJson($expected);
    }

    protected function updateAccountProvider()
    {
        return [
            [
                'new' => [
                    'email' => Api::USER_FIRST,
                    'password' => Api::PASSWORD_SECOND
                ],
                'code' => HttpCode::OK,
                'expected' => [
                    'id' => true,
                    'email' => Api::USER_FIRST,
                ]
            ],
            [
                'name' => 'save with same email',
                'new' => [
                    'email' => true,
                ],
                'code' => HttpCode::OK,
                'expected' => [
                    'id' => true,
                    'email' => true,
                ]
            ],
            [
                'name' => 'empty',
                'new' => [],
                'code' => HttpCode::OK,
                'expected' => [
                    'id' => true,
                    'email' => true,
                ]
            ],
            [
                'name' => 'blank',
                'new' => [
                    'email' => '',
                    'password' => ''
                ],
                'code' => Api::CODE_VALIDATION,
                'expected' => [
                    'email' => Validator::NotBlank()->message,
                    'password' => Validator::NotBlank()->message,
                ]
            ],
            [
                'name' => 'invalid email',
                'new' => [
                    'email' => Api::INVALID_EMAIL,
                ],
                'code' => Api::CODE_VALIDATION,
                'expected' => [
                    'email' => Validator::Email()->message,
                ]
            ],
        ];
    }
}
