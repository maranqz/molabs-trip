<?php

namespace TripBundle\TestsFunctional\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Component\HttpFoundation\Response;
use TripBundle\Entity\Account;
use TripBundle\Tests\Factory\AccountFactory;
use TripBundle\Tests\Factory\TripFactory;
use TripBundle\Tests\Helper\Api;
use Zenstruck\Foundry\Test\ResetDatabase;

class DeleteTest extends ApiTestCase
{
    use ResetDatabase;

    /**
     * @dataProvider deleteAccountProvider
     */
    public function testDelete($authorized, $sameAccount, $code)
    {
        $client = self::createClient();

        $trip = TripFactory::new()->create();
        /** @var Account $account */
        $account = $trip->getCreatedBy();
        $accountId = $account->getId();
        if (!$sameAccount) {
            $accountId = AccountFactory::new()->create()->getId();
        }

        if ($authorized) {
            $client = Api::logIn(self::createClient(), $account);
        }

        $client->request('DELETE', sprintf(API::ACCOUNT.'/%s', $accountId));

        $this->assertResponseStatusCodeSame($code);

        if ($authorized && $sameAccount) {
            AccountFactory::repository()->assertNotExists(['id' => $accountId]);
            TripFactory::repository()->assertNotExists(['id' => $trip->getId()]);
        }
    }

    public function deleteAccountProvider()
    {
        return [
            [
                'authorized' => true,
                'sameAccount' => true,
                'code' => Response::HTTP_NO_CONTENT,
            ],
            'not authorized' => [
                'authorized' => false,
                'sameAccount' => true,
                'code' => Response::HTTP_UNAUTHORIZED,
            ],
            'not own' => [
                'authorized' => true,
                'sameAccount' => false,
                'code' => Response::HTTP_FORBIDDEN,
            ],
        ];
    }
}
