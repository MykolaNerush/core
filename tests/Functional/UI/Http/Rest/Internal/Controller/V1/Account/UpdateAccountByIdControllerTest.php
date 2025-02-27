<?php

declare(strict_types=1);

namespace App\tests\Functional\UI\Http\Rest\Internal\Controller\V1\Account;

use App\Tests\Functional\BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Ramsey\Uuid\Guid\Guid;

class UpdateAccountByIdControllerTest extends BaseTestCase
{
    #[DataProvider('updateAccountsErrorProvider')]
    public function testUpdateAccountError($uuid, $params, $expectedResult): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/v1/internal/accounts/' . $uuid, $params);
        $actual = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals($expectedResult, $actual);
    }

    public static function updateAccountsErrorProvider(): array
    {
        return [
            'Error, not valid fields.' => [
                'e5010159-f361-4ab4-b5a2-4557144e3f11',
                [
                    'name' => 'n',
                    'email' => 'wrongEmail',
                ],
                [
                    'status' => 'error',
                    'message' => 'The name must be at least 2 characters long.',
                    'code' => 500,
                ]
            ],
        ];
    }

    #[DataProvider('successUpdateAccountsProvider')]
    public function testSuccessUpdateAccount($uuid, $params): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/v1/internal/accounts/' . $uuid, $params);
        $this->assertResponseStatusCodeSame(200);

        $client->request('GET', '/api/v1/internal/accounts/' . $uuid);
        $this->assertResponseStatusCodeSame(200);
        $createdAccount = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals($params['accountName'], $createdAccount['data']['attributes']['name']);
    }

    public static function successUpdateAccountsProvider(): array
    {
        return [
            'Success update account' => [
                '2cd95d63-73da-424f-82e4-283f2cd05cd6',
                [
                    'accountName' => 'FOR_UPDATE_ACCOUNTS_1',
                ],
            ],
        ];
    }
}