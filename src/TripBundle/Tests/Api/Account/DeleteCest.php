<?php

namespace Api\Account;

use _fixtures\FackerTrait;
use Codeception\Util\HttpCode;
use TripBundle\Tests\Helper\Api;
use Codeception\Example;
use TripBundle\Tests\ApiTester;

class DeleteCest
{
    use FackerTrait;

    public function _before(ApiTester $I)
    {
        $I->jsonRequest();
    }

    /**
     * TODO add to check deleting of related trips
     *
     * @dataProvider deleteAccountProvider
     */
    public function delete(ApiTester $I, Example $test)
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

        $I->sendDelete(sprintf(API::ACCOUNT . '%s', $accountId));

        $I->seeResponseCodeIs($test['code']);
        if ($test['code'] === HttpCode::OK) {
            $I->seeResponseIsJson();
            $I->seeResponseEquals("true");
        }
    }

    protected function deleteAccountProvider()
    {
        return [
            [
                'authorized' => true,
                'account_id' => true,
                'code' => HttpCode::NO_CONTENT,
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
