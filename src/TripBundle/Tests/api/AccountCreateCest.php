<?php

use Doctrine\ORM\EntityManagerInterface;
use Helper\Api;
use Symfony\Component\Validator\Constraints\NotBlank;
use Codeception\Example;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Email;

class AccountCreateCest
{
    public function _before(ApiTester $I)
    {
        $I->jsonRequest();
    }

    /**
     * @dataProvider userProvider
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

    protected function userProvider()
    {
        $notBlank = new NotBlank();
        $notNull = new NotNull();
        $email = new Email();

        return [
            [
                'send' => [
                    'email' => Api::USER_FIRST,
                    'password' => Api::PASSWORD_FIRST
                ],
                'code' => Api::CODE_OK,
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
                'code' => Api::CODE_OK,
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
                    'password' => $notBlank->message,
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
                    'email' => $email->message,
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
                    'email' => $notBlank->message,
                ]
            ],
            [
                'name' => 'null_email',
                'send' => [
                    'password' => Api::PASSWORD_FIRST,
                ],
                'code' => Api::CODE_VALIDATION,
                'expected' => [
                    'email' => $notNull->message,
                ]
            ]
        ];
    }

    // tests
    public function tryToTest(ApiTester $I)
    {
    }
}
