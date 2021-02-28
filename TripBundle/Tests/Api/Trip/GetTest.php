<?php

namespace TripBundle\Tests\Api\Trip;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Component\HttpFoundation\Response;
use TripBundle\Entity\Account;
use TripBundle\Tests\Factory\AccountFactory;
use TripBundle\Tests\Factory\CountryFactory;
use TripBundle\Tests\Factory\TripFactory;
use TripBundle\Tests\Helper\Api;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class GetTest extends ApiTestCase
{
    use ResetDatabase;
    use Factories;

    /**
     * @dataProvider getTripProvider
     */
    public function testGet($tripId, $code, $authorized = true)
    {
        $client = self::createClient();
        $trip = TripFactory::new()->create();
        if (true === $tripId) {
            $tripId = $trip->getId();
        } else {
            $tripId = TripFactory::new()->create()->getId();
        }

        if ($authorized) {
            $client = Api::logIn($client, $trip->getCreatedBy());
        }

        $client->request('GET', sprintf(API::TRIP.'/%s', $tripId));

        $this->assertResponseStatusCodeSame($code);
        if (Response::HTTP_OK === $code) {
            $this->assertJsonContains([
                'country' => sprintf(API::COUNTRY.'/%s', $trip->getCountry()->getCode()),
                'startedAt' => $trip->getStartedAt()->format(Api::DATE_FORMAT),
                'finishedAt' => $trip->getFinishedAt()->format(Api::DATE_FORMAT),
                'notes' => $trip->getNotes(),
                '@id' => sprintf(API::TRIP.'/%s', $tripId),
            ]);
        }
    }

    public function getTripProvider()
    {
        return [
            [
                'trip_id' => true,
                'code' => Response::HTTP_OK,
            ],
            'not authorized' => [
                'trip_id' => true,
                'code' => Response::HTTP_UNAUTHORIZED,
                'authorized' => false,
            ],
            'not own' => [
                'trip_id' => false,
                'code' => Response::HTTP_NOT_FOUND,
            ],
        ];
    }

    /**
     * @dataProvider getTripsProvider
     */
    public function testGetList($trips, $code, $expectedCount, $filter = [], $authorized = true)
    {
        $client = self::createClient();

        /** @var Account $account */
        $account = AccountFactory::new()->create()->object();
        foreach ($trips as $trip) {
            if (($trip['createdBy'] ?? true) === true) {
                $trip['createdBy'] = $account;
            }

            TripFactory::new()->create($trip);
        }

        if ($authorized) {
            $client = Api::logIn($client, $account);
        }

        $client->request('GET', API::TRIP, ['query' => $filter]);

        $this->assertResponseStatusCodeSame($code);
        if (Response::HTTP_OK === $code) {
            $this->assertJsonContains(['hydra:totalItems' => $expectedCount]);
        }
    }

    public function getTripsProvider()
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
                'trips' => array_fill(0, 3, []),
                'code' => Response::HTTP_OK,
                'expected_count' => 3,
            ],
            [
                'trips' => [],
                'code' => Response::HTTP_UNAUTHORIZED,
                'expected_count' => false,
                'filter' => [],
                'authorized' => false,
            ],
            'different country' => [
                'trips' => [
                    [
                        'country' => CountryFactory::new([
                            'code' => 'one',
                            'name' => 'one',
                            'region' => 'region',
                        ]),
                    ],
                    [
                        'country' => CountryFactory::new([
                            'code' => 'two',
                            'name' => 'two',
                            'region' => 'region',
                        ]),
                    ],
                ],
                'code' => Response::HTTP_OK,
                'expected_count' => 1,
                'filter' => [
                    'country' => 'one',
                ],
            ],
            'started_at' => [
                'trips' => $tripsWithData,
                'code' => Response::HTTP_OK,
                'expected_count' => 1,
                'filter' => [
                    'startedAt[after]' => $date3Str,
                ],
            ],
            'finished_at' => [
                'trips' => $tripsWithData,
                'code' => Response::HTTP_OK,
                'expected_count' => 1,
                'filter' => [
                    'finishedAt[before]' => $date2Str,
                ],
            ],
        ];
    }
}
