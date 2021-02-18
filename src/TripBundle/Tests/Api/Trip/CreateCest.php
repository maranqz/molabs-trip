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

class CreateCest
{
    public function _before(ApiTester $I)
    {
        $I->jsonRequest();
    }

    /**
     * @dataProvider createTripProvider
     */
    public function create(ApiTester $I, Example $test)
    {
        $expected = $test['expected'];
        $send = $test['send'];
        if ($test['authorized'] ?? true) {
            $account = $I->getAccount();
            if (($expected['created_by'] ?? false) === true) {
                $expected['created_by'] = $account->getId();
            }
            $I->amHttpAuthenticated($account->getEmail(), $account->getPlainPassword());
        }
        if ($send['country'] === true) {
            $send['country'] = $I->getCountry()->getCode();
        }


        $I->sendPost(API::TRIP, $send);

        $I->seeResponseCodeIs($test['code']);


        if (!($test['authorized'] ?? true)) {
            return;
        }


        $I->seeResponseIsJson();

        if ($expected['id'] ?? false) {
            /** @var EntityManagerInterface $em */
            $em = $I->grabService('doctrine');

            $expected['id'] = intval($em->getConnection()->lastInsertId());
        }

        if (($expected['country'] ?? false) === true) {
            $expected['country'] = $send['country'];
        }

        $I->seeResponseContainsJson($expected);
    }

    protected function createTripProvider()
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
                    'notes' => '',
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
                    'country' => true,
                ],
                'code' => Api::CODE_VALIDATION,
                'expected' => [
                    'started_at' => Validator::NotNull()->message,
                    'finished_at' => Validator::NotNull()->message,
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
                'name' => 'empty country',
                'send' => [
                    'country' => null,
                    'started_at' => $date1Str,
                    'finished_at' => $date2Str,
                ],
                'code' => Api::CODE_VALIDATION,
                'expected' => [
                    'country' => Validator::NotNull()->message,
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

    public function createWithOverlapping(ApiTester $I)
    {
        $trip = $I->getTrip();

        $account = $trip->getCreatedBy();
        $I->amHttpAuthenticated($account->getEmail(), $account->getPlainPassword());

        $I->sendPost(API::TRIP, [
            'country' => $trip->getCountry()->getCode(),
            'started_at' => $trip->getStartedAt()->format(Api::DATE_FORMAT),
            'finished_at' => $trip->getFinishedAt()->format(Api::DATE_FORMAT),
        ]);

        $I->seeResponseCodeIs(Api::CODE_VALIDATION);
        $I->seeResponseIsJson();

        $I->seeResponseContainsJson([
            'started_at' => Validator::NotOverlapping()->message
        ]);
    }
}
