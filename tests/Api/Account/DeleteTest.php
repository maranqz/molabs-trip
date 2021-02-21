<?php


namespace App\TestsFunctional\Api;


use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Account;
use App\Tests\Factory\AccountFactory;
use Symfony\Component\HttpFoundation\Response;
use App\Tests\Helper\Api;
use Zenstruck\Foundry\Test\ResetDatabase;

class DeleteTest extends ApiTestCase
{
    use ResetDatabase;

    /**
     * TODO add to check deleting of related trips
     *
     * @dataProvider deleteAccountProvider
     */
    public function testDelete($authorized, $accountId, $code)
    {
        $client = self::createClient();

        /** @var Account $account */
        $account = AccountFactory::new()->create()->object();
        if ($accountId === true) {
            $accountId = $account->getId();
        } else {
            $accountId = AccountFactory::new()->create()->getId();
        }

        if ($authorized) {
            $client = Api::logIn(self::createClient(), $account);
        }

        $client->request('DELETE', sprintf(API::ACCOUNT . '/%s', $accountId));

        $this->assertResponseStatusCodeSame($code);
    }

    public function deleteAccountProvider()
    {
        return [
            [
                'authorized' => true,
                'accountId' => true,
                'code' => Response::HTTP_NO_CONTENT,
            ],
            'not authorized' => [
                'authorized' => false,
                'accountId' => true,
                'code' => Response::HTTP_UNAUTHORIZED,
            ],
            'not own' => [
                'authorized' => true,
                'accountId' => false,
                'code' => Response::HTTP_FORBIDDEN,
            ],
        ];
    }
}