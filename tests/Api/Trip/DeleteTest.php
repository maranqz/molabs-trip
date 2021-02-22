<?php

namespace App\Tests\Api\Trip;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\Factory\TripFactory;
use App\Tests\Helper\Api;
use Symfony\Component\HttpFoundation\Response;
use Zenstruck\Foundry\Test\ResetDatabase;

class DeleteTest extends ApiTestCase
{
    use ResetDatabase;

    /**
     * @dataProvider deleteTripProvider
     */
    public function testDelete($authorized, $tripId, $code)
    {
        $client = self::createClient();

        $trip = TripFactory::new()->create();
        if ($tripId === true) {
            $tripId = $trip->getId();
        } else {
            $tripId = TripFactory::new()->create()->getId();
        }

        if ($authorized) {
            $client = Api::logIn($client, $trip->getCreatedBy());
        }

        $client->request('DELETE', sprintf(Api::TRIP . '/%s', $tripId));

        $this->assertResponseStatusCodeSame($code);
    }

    public function deleteTripProvider()
    {
        return [
            [
                'authorized' => true,
                'tripId'     => true,
                'code'       => Response::HTTP_NO_CONTENT,
            ],
            'not authorized' => [
                'authorized' => false,
                'tripId'     => true,
                'code'       => Response::HTTP_UNAUTHORIZED,
            ],
            'not own'        => [
                'authorized' => true,
                'tripId'     => false,
                'code'       => Response::HTTP_NOT_FOUND,
            ],
        ];
    }
}
