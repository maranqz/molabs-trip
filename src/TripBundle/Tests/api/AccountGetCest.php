<?php

use _fixtures\AccountFixtures;
use _fixtures\FackerTrait;
use Codeception\Util\HttpCode;
use Helper\Api;
use Codeception\Example;

class AccountGetCest
{
    use FackerTrait;

    public function _before(ApiTester $I)
    {
        $I->jsonRequest();
    }

    /**
     * @dataProvider getUserProvider
     */
    public function get(ApiTester $I, Example $test)
    {
        $account = $I->getAccount();
        $accountId = $test['account_id'];
        if ($accountId === true) {
            $accountId = $account->getId();
        } else {
            $accountId = $I->getAccount()->getId();
        }

        if ($test['authorized']) {
            $I->amHttpAuthenticated($account->getEmail(), $account->getPlainPassword());
        }

        $I->sendGet(sprintf(API::PREFIX . '/accounts/%s', $accountId));

        $I->seeResponseCodeIs($test['code']);
        if ($test['code'] === HttpCode::OK) {
            $I->seeResponseIsJson();
            $I->seeResponseContainsJson([
                'email' => $account->getEmail(),
                'id' => $account->getId(),
            ]);
        }
    }

    protected function getUserProvider()
    {
        return [
            [
                'authorized' => true,
                'account_id' => true,
                'code' => HttpCode::OK,
            ],
            [
                'name' => 'not authorized',
                'authorized' => false,
                'account_id' => true,
                'code' => HttpCode::UNAUTHORIZED,
            ],
            [
                'name' => 'not own',
                'authorized' => true,
                'account_id' => false,
                'code' => HttpCode::FORBIDDEN,
            ],
        ];
    }
}
