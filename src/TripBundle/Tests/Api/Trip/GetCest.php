<?php

namespace TripBundle\Tests\Api\Trip;

use _fixtures\FackerTrait;
use Codeception\Util\HttpCode;
use TripBundle\Tests\Helper\Api;
use Codeception\Example;
use TripBundle\Tests\ApiTester;

class GetCest
{
    use FackerTrait;

    public function _before(ApiTester $I)
    {
        $I->jsonRequest();
    }

    /**
     * @dataProvider getTripProvider
     */
    public function get(ApiTester $I, Example $test)
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

        $I->sendGet(sprintf(API::TRIP . '%s', $tripId));

        $I->seeResponseCodeIs($test['code']);
        if ($test['code'] === HttpCode::OK) {
            $I->seeResponseIsJson();
            $I->seeResponseContainsJson([
                'country' => $trip->getCountry()->getCode(),
                'started_at' => $trip->getStartedAt()->format(Api::DATE_FORMAT),
                'finished_at' => $trip->getFinishedAt()->format(Api::DATE_FORMAT),
                'created_by' => $trip->getCreatedBy()->getId(),
                'notes' => $trip->getNotes(),
                'id' => $trip->getId(),
            ]);
        }
    }

    protected function getTripProvider()
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

    /**
     * @dataProvider getTripsProvider
     */
    public function getList(ApiTester $I, Example $test)
    {
        $account = $I->getAccount();
        foreach ($test['trips'] as $trip) {
            if (($trip['createdBy'] ?? true) === true) {
                $trip['createdBy'] = $account;
            }

            $I->getTrip($trip);
        }

        if ($test['authorized']) {
            $I->amHttpAuthenticated(
                $account->getEmail(),
                $account->getPlainPassword()
            );
        }

        $I->sendGet(API::TRIP, $test['filter']);

        $I->seeResponseCodeIs($test['code']);
        if ($test['code'] === HttpCode::OK) {
            $I->seeResponseIsJson();
            $response = $I->grabDataFromResponseByJsonPath('$.*');
            $I->assertCount($test['expected_count'], $response);
        }
    }

    protected function getTripsProvider()
    {
        $date1 = (new \DateTimeImmutable())->setTime(0, 0, 0, 0);
        $date2 = $date1->add(new \DateInterval('P1D'));
        $date2Str = $date2->format(Api::DATE_FORMAT);
        $date3 = $date2->add(new \DateInterval('P1D'));
        $date3Str = $date3->format(Api::DATE_FORMAT);
        $date4 = $date3->add(new \DateInterval('P1D'));

        $tripsWithData = [
            [
                'startedAt' => $date1,
                'finishedAt' => $date2,
            ],
            [
                'startedAt' => $date3,
                'finishedAt' => $date4,
            ],
        ];

        return [
            [
                'authorized' => true,
                'filter' => [],
                'trips' => array_fill(0, 3, []),
                'code' => HttpCode::OK,
                'expected_count' => 3,
            ],
            [
                'authorized' => false,
                'filter' => [],
                'trips' => [],
                'code' => HttpCode::UNAUTHORIZED,
                'expected_count' => false,
            ],
            [
                'name' => 'different country',
                'authorized' => true,
                'filter' => [
                    'country' => 'one',
                ],
                'trips' => [
                    ['country' => ['code' => 'one', 'name' => 'name', 'region' => 'region']],
                    ['country' => ['code' => 'two', 'name' => 'name', 'region' => 'region']],
                ],
                'code' => HttpCode::OK,
                'expected_count' => 1,
            ],
            [
                'name' => 'started_at',
                'authorized' => true,
                'filter' => [
                    'started_at' => $date3Str,
                ],
                'trips' => $tripsWithData,
                'code' => HttpCode::OK,
                'expected_count' => 1,
            ],
            [
                'name' => 'finished_at',
                'authorized' => true,
                'filter' => [
                    'finished_at' => $date2Str,
                ],
                'trips' => $tripsWithData,
                'code' => HttpCode::OK,
                'expected_count' => 1,
            ],
        ];
    }
}
