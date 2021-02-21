<?php


namespace App\TestsFunctional\Api;


use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Account;
use App\Tests\Factory\AccountFactory;
use App\Tests\Helper\Validator;
use Symfony\Component\HttpFoundation\Response;
use App\Tests\Helper\Api;
use Zenstruck\Foundry\Test\ResetDatabase;

class UpdateTest extends ApiTestCase
{
    use ResetDatabase;

    public function testUpdateUnAuthorized()
    {
        /** @var Account $account */
        $account = AccountFactory::new()->create()->object();

        $client = self::createClient();
        $client->request('PUT', sprintf(API::ACCOUNT . '/%s', $account->getId()), [
            'json' => ['email' => AccountFactory::USER_SECOND]
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @dataProvider updateAccountProvider
     */
    public function testUpdate($send, $code, $expected)
    {
        /** @var Account $account */
        $account = AccountFactory::new()->create()->object();

        $client = Api::logIn(self::createClient(), $account);

        if (($send['email'] ?? false) === true) {
            $send['email'] = $account->getEmail();
        }

        $client->request('PUT', sprintf(API::ACCOUNT . '/%s', $account->getId()), [
            'json' => $send,
        ]);

        $this->assertResponseStatusCodeSame($code);

        if (($expected['@id'] ?? false) === true) {
            $expected['@id'] = Api::ACCOUNT . '/' . $account->getId();
        }
        if (($expected['email'] ?? false) === true) {
            $expected['email'] = $account->getEmail();
        }
        if (($expected['username'] ?? false) === true) {
            $expected['username'] = $account->getUsername();
        }

        $this->assertJsonContains($expected);
    }

    public function updateAccountProvider()
    {
        return [
            [
                'send'     => [
                    'email'    => AccountFactory::USER_FIRST,
                    'password' => AccountFactory::DEFAULT_PASSWORD
                ],
                'code'     => Response::HTTP_OK,
                'expected' => [
                    '@id'      => true,
                    'email'    => AccountFactory::USER_FIRST,
                    'username' => true,
                ]
            ],
            'save with same email' => [
                'send'     => [
                    'email' => true,
                ],
                'code'     => Response::HTTP_OK,
                'expected' => [
                    '@id'      => true,
                    'email'    => true,
                    'username' => true,
                ]
            ],
            'empty'                => [
                'send'     => [],
                'code'     => Response::HTTP_OK,
                'expected' => [
                    '@id'      => true,
                    'email'    => true,
                    'username' => true,
                ]
            ],
            'blank password'       => [
                'send'     => [
                    'password' => '',
                ],
                'code'     => Response::HTTP_OK,
                'expected' => [
                    '@id'      => true,
                    'email'    => true,
                    'username' => true,
                ]
            ],
            'blank'                => [
                'send'     => [
                    'email'    => '',
                    'username' => '',
                ],
                'code'     => Api::CODE_VALIDATION,
                'expected' => [
                    'violations' => [
                        ['propertyPath' => 'email', 'message' => Validator::NotBlank()->message],
                        ['propertyPath' => 'username', 'message' => Validator::NotBlank()->message],
                    ]
                ]
            ],
            'invalid email'        => [
                'send'     => [
                    'email' => AccountFactory::INVALID_EMAIL,
                ],
                'code'     => Api::CODE_VALIDATION,
                'expected' => [
                    'violations' => [
                        ['propertyPath' => 'email', 'message' => Validator::Email()->message],
                    ]
                ]
            ],
        ];
    }
}