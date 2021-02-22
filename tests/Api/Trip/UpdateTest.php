<?php

namespace App\Tests\Api\Trip;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\Factory\CountryFactory;
use App\Tests\Factory\TripFactory;
use App\Tests\Helper\Api;
use App\Tests\Helper\Validator;
use Symfony\Component\HttpFoundation\Response;
use Zenstruck\Foundry\Test\ResetDatabase;

class UpdateTest extends ApiTestCase
{
    use ResetDatabase;

    /**
     * @dataProvider updateTripProvider
     */
    public function testUpdate($send, $code, $expected, $authorized = true)
    {
        $client = self::createClient();

        $trip = TripFactory::new()->create();
        if ($authorized) {
            $client = Api::logIn($client, $trip->getCreatedBy());
        }
        $send['country'] = $send['country'] ?? true;
        if (true === $send['country']) {
            $send['country'] = Api::COUNTRY.'/'.CountryFactory::new()->create()->getCode();
        } elseif (false === $send['country']) {
            $send['country'] = Api::COUNTRY.'/'.$trip->getCountry()->getCode();
        }

        $client->request('PUT', sprintf(Api::TRIP.'/%s', $trip->getId()), ['json' => $send]);
        $this->assertResponseStatusCodeSame($code);

        if (!$authorized) {
            return;
        }

        if (($expected['@id'] ?? false) === true) {
            $expected['@id'] = Api::TRIP.'/'.$trip->getId();
        }
        if (($expected['country'] ?? false) === true) {
            $expected['country'] = $send['country'];
        }
        if (($expected['notes'] ?? false) === true) {
            $expected['notes'] = $trip->getNotes();
        }

        $this->assertJsonContains($expected);
    }

    public function updateTripProvider()
    {
        $date1 = (new \DateTimeImmutable())->setTime(0, 0, 0, 0);
        $date1Str = $date1->format(Api::DATE_FORMAT);
        $date2 = $date1->add(new \DateInterval('P1D'));
        $date2Str = $date2->format(Api::DATE_FORMAT);

        $serializeDateExpected = [
            'hydra:description' => "Parsing datetime string \"123\" using format \"Y-m-d\" resulted in 1 errors: \nat position 3: Data missing",
        ];
        $blankDateExpected = [
            'hydra:description' => 'The data is either an empty string or null, you should pass a string that can be parsed with the passed format or a valid DateTime string.',
        ];

        return [
            'without notes' => [
                'send' => [
                    'country' => true,
                    'startedAt' => $date1Str,
                    'finishedAt' => $date2Str,
                ],
                'code' => Response::HTTP_OK,
                'expected' => [
                    '@id' => true,
                    'country' => true,
                    'startedAt' => $date1Str,
                    'finishedAt' => $date2Str,
                    'notes' => true,
                ],
            ],
            'with notes' => [
                'send' => [
                    'country' => true,
                    'startedAt' => $date1Str,
                    'finishedAt' => $date2Str,
                    'notes' => 'notes',
                ],
                'code' => Response::HTTP_OK,
                'expected' => [
                    '@id' => true,
                    'country' => true,
                    'startedAt' => $date1Str,
                    'finishedAt' => $date2Str,
                    'notes' => 'notes',
                ],
            ],
            'not authorized' => [
                'send' => [
                    'country' => true,
                    'startedAt' => $date1Str,
                    'finishedAt' => $date2Str,
                ],
                'code' => Response::HTTP_UNAUTHORIZED,
                'expected' => false,
                'authorized' => false,
            ],
            'null date' => [
                'send' => [
                ],
                'code' => Response::HTTP_OK,
                'expected' => [
                    'country' => true,
                    '@id' => true,
                ],
            ],
            'not date in startedAt' => [
                'send' => [
                    'country' => true,
                    'startedAt' => '123',
                    'finishedAt' => $date2Str,
                ],
                'code' => Response::HTTP_BAD_REQUEST,
                'expected' => $serializeDateExpected,
            ],
            'blank startedAt' => [
                'send' => [
                    'country' => true,
                    'startedAt' => '',
                ],
                'code' => Response::HTTP_BAD_REQUEST,
                'expected' => $blankDateExpected,
            ],
            'not date in finishedAt' => [
                'send' => [
                    'country' => true,
                    'startedAt' => $date1Str,
                    'finishedAt' => '123',
                ],
                'code' => Response::HTTP_BAD_REQUEST,
                'expected' => $serializeDateExpected,
            ],
            'blank finishedAt' => [
                'send' => [
                    'country' => true,
                    'finishedAt' => '',
                ],
                'code' => Response::HTTP_BAD_REQUEST,
                'expected' => $blankDateExpected,
            ],
            'finishedAt should be great than startedAt' => [
                'send' => [
                    'country' => true,
                    'startedAt' => $date1Str,
                    'finishedAt' => $date1Str,
                    'notes' => 'notes',
                ],
                'code' => Api::CODE_VALIDATION,
                'expected' => [
                    'violations' => [
                        ['propertyPath' => 'finishedAt', 'message' => Validator::GreaterDateMessage($date1)],
                    ],
                ],
            ],
            'invalid country' => [
                'send' => [
                    'country' => Api::COUNTRY.'/SomeNonExist',
                    'startedAt' => $date1Str,
                    'finishedAt' => $date2Str,
                ],
                'code' => Response::HTTP_BAD_REQUEST,
                'expected' => [
                    'hydra:description' => 'Item not found for "/api/countries/SomeNonExist".',
                ],
            ],
        ];
    }
}
