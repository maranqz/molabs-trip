<?php

namespace App\Tests\Api\Trip;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Account;
use App\Entity\Trip;
use App\Tests\Factory\AccountFactory;
use App\Tests\Factory\CountryFactory;
use App\Tests\Factory\TripFactory;
use App\Tests\Helper\Api;
use App\Tests\Helper\Validator;
use Symfony\Component\HttpFoundation\Response;
use Zenstruck\Foundry\Test\ResetDatabase;

class CreateTest extends ApiTestCase
{
    use ResetDatabase;

    /**
     * @dataProvider createTripProvider
     */
    public function testCreate($send, $code, $expected, $authorized = true)
    {
        $client = self::createClient();
        if ($authorized) {
            /** @var Account $account */
            $account = AccountFactory::new()->create()->object();
            Api::logIn($client, $account);
        }
        if ($send['country'] === true) {
            $country         = CountryFactory::new()->create();
            $send['country'] = Api::COUNTRY . '/' . $country->getCode();
        }


        $client->request('POST', Api::TRIP, ['json' => $send]);

        $this->assertResponseStatusCodeSame($code);


        if ( ! $authorized) {
            return;
        }

        if ($expected['@id'] ?? false) {
            /** @var Trip $trip */
            $trip            = TripFactory::repository()->last();
            $expected['@id'] = Api::TRIP . '/' . $trip->getId();
        }

        if (($expected['country'] ?? false) === true) {
            $expected['country'] = $send['country'];
        }

        $this->assertJsonContains($expected);
    }

    public function createTripProvider()
    {
        $date1    = (new \DateTimeImmutable())->setTime(0, 0, 0, 0);
        $date1Str = $date1->format(Api::DATE_FORMAT);
        $date2    = $date1->add(new \DateInterval('P1D'));
        $date2Str = $date2->format(Api::DATE_FORMAT);

        $serializeDateExpected = [
            'hydra:description' => "Parsing datetime string \"123\" using format \"Y-m-d\" resulted in 1 errors: \nat position 3: Data missing"
        ];
        $blankDateExpected     = [
            'hydra:description' => 'The data is either an empty string or null, you should pass a string that can be parsed with the passed format or a valid DateTime string.'
        ];

        return [
            'without notes'                             => [
                'send'     => [
                    'country'    => true,
                    'startedAt'  => $date1Str,
                    'finishedAt' => $date2Str,
                ],
                'code'     => Response::HTTP_CREATED,
                'expected' => [
                    '@id'        => true,
                    'country'    => true,
                    'startedAt'  => $date1Str,
                    'finishedAt' => $date2Str,
                    'notes'      => null,
                ]
            ],
            'with notes'                                => [
                'send'     => [
                    'country'    => true,
                    'startedAt'  => $date1Str,
                    'finishedAt' => $date2Str,
                    'notes'      => 'notes',
                ],
                'code'     => Response::HTTP_CREATED,
                'expected' => [
                    '@id'        => true,
                    'country'    => true,
                    'startedAt'  => $date1Str,
                    'finishedAt' => $date2Str,
                    'notes'      => 'notes',
                ]
            ],
            'not authorized'                            => [
                'send'       => [
                    'country'    => true,
                    'startedAt'  => $date1Str,
                    'finishedAt' => $date2Str,
                ],
                'code'       => Response::HTTP_UNAUTHORIZED,
                'expected'   => false,
                'authorized' => false,
            ],
            'null date'                                 => [
                'send'     => [
                    'country' => true,
                ],
                'code'     => Api::CODE_VALIDATION,
                'expected' => [
                    'violations' => [
                        ['propertyPath' => 'startedAt', 'message' => Validator::NotNull()->message],
                        ['propertyPath' => 'finishedAt', 'message' => Validator::NotNull()->message],
                    ]
                ],
            ],
            'not date in startedAt'                     => [
                'send'     => [
                    'country'    => true,
                    'startedAt'  => '123',
                    'finishedAt' => $date2Str,
                ],
                'code'     => Response::HTTP_BAD_REQUEST,
                'expected' => $serializeDateExpected,
            ],
            'blank startedAt'                           => [
                'send'     => [
                    'country'   => true,
                    'startedAt' => '',
                ],
                'code'     => Response::HTTP_BAD_REQUEST,
                'expected' => $blankDateExpected,
            ],
            'not date in finishedAt'                    => [
                'send'     => [
                    'country'    => true,
                    'startedAt'  => $date1Str,
                    'finishedAt' => '123',
                ],
                'code'     => Response::HTTP_BAD_REQUEST,
                'expected' => $serializeDateExpected,
            ],
            'blank finishedAt'                          => [
                'send'     => [
                    'country'    => true,
                    'finishedAt' => '',
                ],
                'code'     => Response::HTTP_BAD_REQUEST,
                'expected' => $blankDateExpected,
            ],
            'finishedAt should be great than startedAt' => [
                'send'     => [
                    'country'    => true,
                    'startedAt'  => $date1Str,
                    'finishedAt' => $date1Str,
                    'notes'      => 'notes',
                ],
                'code'     => Api::CODE_VALIDATION,
                'expected' => [
                    'violations' => [
                        ['propertyPath' => 'finishedAt', 'message' => Validator::GreaterDateMessage($date1)],
                    ]
                ]
            ],
            'empty country'                             => [
                'send'     => [
                    'country'    => null,
                    'startedAt'  => $date1Str,
                    'finishedAt' => $date2Str,
                ],
                'code'     => Response::HTTP_BAD_REQUEST,
                'expected' => [
                    'hydra:description' => 'Expected IRI or nested document for attribute "country", "NULL" given.'
                ]
            ],
            'invalid country'                           => [
                'send'     => [
                    'country'    => Api::COUNTRY . '/SomeNonExist',
                    'startedAt'  => $date1Str,
                    'finishedAt' => $date2Str,
                ],
                'code'     => Response::HTTP_BAD_REQUEST,
                'expected' => [
                    'hydra:description' => 'Item not found for "/api/countries/SomeNonExist".'
                ]
            ],
        ];
    }

    public function testCreateWithOverlapping()
    {
        $client  = self::createClient();
        $trip    = TripFactory::new()->create();
        $account = $trip->getCreatedBy();

        Api::logIn($client, $account);

        $client->request('POST', Api::TRIP, [
            'json' => [
                'country'    => Api::COUNTRY . '/' . $trip->getCountry()->getCode(),
                'startedAt'  => $trip->getStartedAt()->format(Api::DATE_FORMAT),
                'finishedAt' => $trip->getFinishedAt()->format(Api::DATE_FORMAT),
            ],
        ]);

        $this->assertResponseStatusCodeSame(Api::CODE_VALIDATION);

        $this->assertJsonContains([
            'violations' => [['propertyPath' => 'startedAt', 'message' => Validator::NotOverlapping()->message]],
        ]);
    }
}
