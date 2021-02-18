<?php

namespace Api\Trip;

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
     * @dataProvider deleteTripProvider
     */
    public function delete(ApiTester $I, Example $test)
    {
        $trip = $I->getTrip();
        $tripId = $test['trip_id'];
        if ($tripId === true) {
            $tripId = $trip->getId();
        } else {
            $tripId = $I->getTrip()->getId();
        }

        if ($test['authorized']) {
            $I->amHttpAuthenticated(
                $trip->getCreatedBy()->getEmail(),
                $trip->getCreatedBy()->getPlainPassword()
            );
        }

        $I->sendDelete(sprintf(API::TRIP . '%s', $tripId));

        $I->seeResponseCodeIs($test['code']);
        if ($test['code'] === HttpCode::OK) {
            $I->seeResponseIsJson();
            $I->seeResponseEquals("true");
        }
    }

    protected function deleteTripProvider()
    {
        return [
            [
                'authorized' => true,
                'trip_id' => true,
                'code' => HttpCode::OK,
            ],
            [
                'name' => 'not authorized',
                'authorized' => false,
                'trip_id' => true,
                'code' => HttpCode::UNAUTHORIZED,
            ],
            [
                'name' => 'not own',
                'authorized' => true,
                'trip_id' => false,
                'code' => HttpCode::FORBIDDEN,
            ],
        ];
    }
}
