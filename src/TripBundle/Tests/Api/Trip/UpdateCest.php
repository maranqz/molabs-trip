<?php

namespace TripBundle\Tests\Api\Trip;

use Codeception\Util\HttpCode;
use Doctrine\ORM\EntityManagerInterface;
use TripBundle\Entity\Country;
use TripBundle\Tests\ApiTester;
use TripBundle\Tests\Helper\Api;
use TripBundle\Tests\Helper\Validator;
use Codeception\Example;
use TripBundle\Entity\Account;

class UpdateCest
{
    public function _before(ApiTester $I)
    {
        $I->jsonRequest();
    }

    /**
     * @dataProvider updateTripProvider
     */
    public function update(ApiTester $I, Example $test)
    {
        $trip = $I->getTrip();
        $expected = $test['expected'];
        $send = $test['send'];

        if ($test['authorized'] ?? true) {
            $I->amHttpAuthenticated($trip->getCreatedBy()->getEmail(), $trip->getCreatedBy()->getPlainPassword());
        }
        $send['country'] = $send['country'] ?? true;
        if ($send['country'] === true) {
            $send['country'] = $I->getCountry()->getCode();
        }
        if ($send['country'] === false) {
            $send['country'] = $trip->getCountry()->getCode();
        }


        $I->sendPatch(sprintf(API::TRIP . '%s', $trip->getId()), $send);

        $I->seeResponseCodeIs($test['code']);


        if (!($test['authorized'] ?? true)) {
            return;
        }


        $I->seeResponseIsJson();


        if (($expected['id'] ?? false) === true) {
            $expected['id'] = $trip->getId();
        }
        if (($expected['country'] ?? false) === true) {
            $expected['country'] = $send['country'];
        }
        if (($expected['created_by'] ?? false) === true) {
            $expected['created_by'] = $trip->getCreatedBy()->getId();
        }
        if (($expected['notes'] ?? false) === true) {
            $expected['notes'] = $trip->getNotes();
        }

        $I->seeResponseContainsJson($expected);
    }

    protected function updateTripProvider()
    {
        $date1 = (new \DateTimeImmutable())->setTime(0, 0, 0, 0);
        $date1Str = $date1->format(Api::DATE_FORMAT);
        $date2 = $date1->add(new \DateInterval('P1D'));
        $date2Str = $date2->format(Api::DATE_FORMAT);

        $serializeDateExpected = [
            'message' => 'Invalid datetime "123", expected format Y-m-d.'
        ];
        $blankDateExpected = [
            'message' => 'Invalid datetime "", expected format Y-m-d.'
        ];

        return [
            [
                'name' => 'without notes',
                'send' => [
                    'country' => true,
                    'started_at' => $date1Str,
                    'finished_at' => $date2Str,
                ],
                'code' => HttpCode::OK,
                'expected' => [
                    'id' => true,
                    'country' => true,
                    'started_at' => $date1Str,
                    'finished_at' => $date2Str,
                    'notes' => true,
                    'created_by' => true,
                ]
            ],
            [
                'name' => 'with notes',
                'send' => [
                    'country' => true,
                    'started_at' => $date1Str,
                    'finished_at' => $date2Str,
                    'notes' => 'notes',
                ],
                'code' => HttpCode::OK,
                'expected' => [
                    'id' => true,
                    'country' => true,
                    'started_at' => $date1Str,
                    'finished_at' => $date2Str,
                    'notes' => 'notes',
                    'created_by' => true,
                ]
            ],
            [
                'name' => 'not authorized',
                'authorized' => false,
                'send' => [
                    'country' => true,
                    'started_at' => $date1Str,
                    'finished_at' => $date2Str,
                ],
                'code' => HttpCode::UNAUTHORIZED,
                'expected' => false,
            ],
            [
                'name' => 'null date',
                'send' => [
                ],
                'code' => HttpCode::OK,
                'expected' => [
                    'country' => true,
                    'id' => true,
                ],
            ],
            [
                'name' => 'not date in started_at',
                'send' => [
                    'country' => true,
                    'started_at' => '123',
                    'finished_at' => $date2Str,
                ],
                'code' => Api::CODE_VALIDATION,
                'expected' => $serializeDateExpected,
            ],
            [
                'name' => 'blank started_at',
                'send' => [
                    'country' => true,
                    'started_at' => '',
                ],
                'code' => Api::CODE_VALIDATION,
                'expected' => $blankDateExpected,
            ],
            [
                'name' => 'not date in finished_at',
                'send' => [
                    'country' => true,
                    'started_at' => $date1Str,
                    'finished_at' => '123',
                ],
                'code' => Api::CODE_VALIDATION,
                'expected' => $serializeDateExpected,
            ],
            [
                'name' => 'blank finished_at',
                'send' => [
                    'country' => true,
                    'finished_at' => '',
                ],
                'code' => Api::CODE_VALIDATION,
                'expected' => $blankDateExpected,
            ],
            [
                'name' => 'finished_at should be great than started_at',
                'send' => [
                    'country' => true,
                    'started_at' => $date1Str,
                    'finished_at' => $date1Str,
                    'notes' => 'notes',
                ],
                'code' => Api::CODE_VALIDATION,
                'expected' => [
                    'finished_at' => Validator::GreaterDateMessage($date1),
                ]
            ],
            [
                'name' => 'invalid country',
                'send' => [
                    'country' => 'some none exist',
                    'started_at' => $date1Str,
                    'finished_at' => $date2Str,
                ],
                'code' => Api::CODE_VALIDATION,
                'expected' => [
                    'country' => Validator::EntityExistMessage(Country::class),
                ]
            ],
        ];
    }
}
