<?php

use Codeception\Util\HttpCode;
use Doctrine\ORM\EntityManagerInterface;
use Helper\Api;
use Helper\Validator;
use Codeception\Example;
use TripBundle\Entity\Account;

class AccountCreateCest
{
    public function _before(ApiTester $I)
    {
        $I->jsonRequest();
    }

    /**
     * @dataProvider createUserProvider
     */
    public function create(ApiTester $I, Example $test)
    {
        $I->sendPost(API::PREFIX . '/accounts/', $test['send']);

        $I->seeResponseCodeIs($test['code']);
        $I->seeResponseIsJson();

        $expected = $test['expected'];
        if ($expected['id'] ?? false) {
            /** @var EntityManagerInterface $em */
            $em = $I->grabService('doctrine');

            $expected['id'] = $em->getConnection()->lastInsertId();
        }

        $I->seeResponseContainsJson($expected);
    }

    protected function createUserProvider()
    {
        return [
            [
                'send' => [
                    'email' => Api::USER_FIRST,
                    'password' => Api::PASSWORD_FIRST
                ],
                'code' => HttpCode::OK,
                'expected' => [
                    'email' => Api::USER_FIRST,
                    'id' => true,
                ]
            ],
            [
                'send' => [
                    'email' => Api::USER_SECOND,
                    'password' => Api::PASSWORD_SECOND,
                ],
                'code' => HttpCode::OK,
                'expected' => [
                    'email' => Api::USER_SECOND,
                    'id' => true,
                ]
            ],
            [
                'name' => 'blank_password',
                'send' => [
                    'email' => Api::USER_SECOND,
                    'password' => '',
                ],
                'code' => Api::CODE_VALIDATION,
                'expected' => [
                    'password' => Validator::NotBlank()->message,
                ]
            ],
            [
                'name' => 'invalid_email',
                'send' => [
                    'email' => Api::INVALID_EMAIL,
                    'password' => Api::PASSWORD_FIRST,
                ],
                'code' => Api::CODE_VALIDATION,
                'expected' => [
                    'email' => Validator::Email()->message,
                ]
            ],
            [
                'name' => 'blank_email',
                'send' => [
                    'email' => '',
                    'password' => Api::PASSWORD_FIRST,
                ],
                'code' => Api::CODE_VALIDATION,
                'expected' => [
                    'email' => Validator::NotBlank()->message,
                ]
            ],
            [
                'name' => 'null_email',
                'send' => [
                    'password' => Api::PASSWORD_FIRST,
                ],
                'code' => Api::CODE_VALIDATION,
                'expected' => [
                    'email' => Validator::NotNull()->message,
                ]
            ]
        ];
    }

    public function createExistUser(ApiTester $I)
    {
        $user = new Account();
        $user->setEmail(Api::USER_FIRST);
        $user->setPassword(Api::PASSWORD_FIRST);

        $I->persistEntity($user);
        $I->flushToDatabase();

        $I->sendPost(API::PREFIX . '/accounts/', [
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
        ]);

        $I->seeResponseCodeIs(Api::CODE_VALIDATION);
        $I->seeResponseIsJson([
            'email' => Validator::UniqueEntity()->message,
        ]);
    }

    public function passwordIsHashed(ApiTester $I)
    {
        $password = Api::PASSWORD_FIRST;
        $I->sendPost(API::PREFIX . '/accounts/', [
            'email' => Api::USER_FIRST,
            'password' => $password,
        ]);

        /** @var EntityManagerInterface $em */
        $em = $I->grabService('doctrine');

        /** @var Account $account */
        $account = $em->getRepository(Account::class)
            ->find($em->getConnection()->lastInsertId());

        $I->assertNotEquals($password, $account->getPassword());
    }
}
