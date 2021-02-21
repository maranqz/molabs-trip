<?php


namespace App\TestsFunctional\Api;


use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Account;
use App\Tests\Factory\AccountFactory;
use App\Tests\Helper\Validator;
use Symfony\Component\HttpFoundation\Response;
use App\Tests\Helper\Api;
use Zenstruck\Foundry\Test\ResetDatabase;

class CreateTest extends ApiTestCase
{
    use ResetDatabase;

    /**
     * @dataProvider createAccountProvider
     */
    public function testCreate($send, $code, $expected)
    {
        $client = self::createClient();
        $client->request('POST', API::ACCOUNT, [
            'json' => $send
        ]);
        $this->assertResponseStatusCodeSame($code);


        if ($expected['@id'] ?? false) {
            $account = AccountFactory::repository()->findOneBy(['email' => $send['email']]);
            $this->assertNotNull($account);

            $expected['@id'] = Api::ACCOUNT . '/' . $account->getId();
        }

        $this->assertJsonContains($expected);
    }

    public function createAccountProvider()
    {
        return [
            'success for first user'  => [
                'send'     => [
                    'email'    => AccountFactory::USER_FIRST,
                    'username' => AccountFactory::USER_FIRST,
                    'password' => AccountFactory::DEFAULT_PASSWORD
                ],
                'code'     => Response::HTTP_CREATED,
                'expected' => [
                    'email'    => AccountFactory::USER_FIRST,
                    'username' => AccountFactory::USER_FIRST,
                    '@id'      => true,
                ]
            ],
            'success for second user' => [
                'send'       => [
                    'email'    => AccountFactory::USER_SECOND,
                    'username' => AccountFactory::USER_SECOND,
                    'password' => AccountFactory::DEFAULT_PASSWORD,
                ],
                'code'       => Response::HTTP_CREATED,
                'violations' => [
                    'email'    => AccountFactory::USER_SECOND,
                    'username' => AccountFactory::USER_SECOND,
                    '@id'      => true,
                ]
            ],
            'blank'                   => [
                'send'     => [
                    'email'    => '',
                    'username' => '',
                    'password' => '',
                ],
                'code'     => Api::CODE_VALIDATION,
                'expected' => [
                    'violations' => [
                        ['propertyPath' => 'email', 'message' => Validator::NotBlank()->message],
                        ['propertyPath' => 'username', 'message' => Validator::NotBlank()->message],
                        ['propertyPath' => 'password', 'message' => Validator::NotBlank()->message],
                    ]
                ]
            ],
            'invalid_email'           => [
                'send'     => [
                    'email' => AccountFactory::INVALID_EMAIL,
                ],
                'code'     => Api::CODE_VALIDATION,
                'expected' => [
                    'violations' => [
                        ['propertyPath' => 'email', 'message' => Validator::Email()->message]
                    ]
                ]
            ],
            'null'                    => [
                'send'     => [],
                'code'     => Api::CODE_VALIDATION,
                'expected' => [
                    'violations' => [
                        ['propertyPath' => 'email', 'message' => Validator::NotBlank()->message],
                        ['propertyPath' => 'username', 'message' => Validator::NotBlank()->message],
                        ['propertyPath' => 'password', 'message' => Validator::NotBlank()->message],
                    ]
                ]
            ]
        ];
    }

    public function testCreateExistUser()
    {
        $client  = self::createClient();
        $account = AccountFactory::new()->create([
            'email'    => AccountFactory::USER_FIRST,
            'username' => AccountFactory::USER_FIRST,
            'password' => AccountFactory::DEFAULT_PASSWORD,
        ]);

        $client->request('POST', API::ACCOUNT, [
            'json' => [
                'email'    => $account->getEmail(),
                'username' => $account->getUsername(),
                'password' => $account->getPassword(),
            ],
        ]);

        $this->assertResponseStatusCodeSame(Api::CODE_VALIDATION);
        $this->assertJsonContains([
            'violations' => [
                ['propertyPath' => 'username', 'message' => Validator::UniqueEntity()->message],
                ['propertyPath' => 'email', 'message' => Validator::UniqueEntity()->message],
            ]
        ]);
    }

    public function testPasswordIsHashed()
    {
        $client  = self::createClient();
        $password = AccountFactory::DEFAULT_PASSWORD;
        $client->request('POST', API::ACCOUNT, [
            'json' => [
                'email'    => AccountFactory::USER_FIRST,
                'username' => AccountFactory::USER_FIRST,
                'password' => $password,
            ],
        ]);

        /** @var Account $account */
        $account = AccountFactory::repository()->find(['email' => AccountFactory::USER_FIRST]);

        $this->assertNotEquals($password, $account->getPassword());
    }
}