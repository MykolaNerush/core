<?php

declare(strict_types=1);

namespace App\tests\Functional\UI\Http\Rest\Internal\Controller\V1\Account;

use App\Tests\Functional\BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Ramsey\Uuid\Guid\Guid;

class CreateAccountControllerTest extends BaseTestCase
{
    #[DataProvider('createAccountErrorProvider')]
    public function testCreateAccountError($params, $expectedResult): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/v1/internal/accounts', $params);
        $actual = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals($expectedResult, $actual);
    }

    public static function createAccountErrorProvider(): array
    {
        return [
            'Error, required fields are missing.' => [
                [
                ],
                [
                    'status' => 'error',
                    'message' => 'The name field is required.',
                    'code' => 500,
                ]
            ],
        ];

    }


    #[DataProvider('successCreateAccountProvider')]
    public function testSuccessCreateAccounts($params, $expectedResult): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/v1/internal/accounts', $params);
        $createdAccountId = json_decode($client->getResponse()->getContent(), true);
        $this->assertTrue(
            Guid::isValid($createdAccountId['id']),
            'The returned id is not a valid UUID.'
        );
        $client->request('GET', '/api/v1/internal/accounts/' . $createdAccountId['id']);
        $createdAccount = json_decode($client->getResponse()->getContent(), true)['data'];
        $this->assertResponseStatusCodeSame(200);
        $this->assertEquals($expectedResult['type'], $createdAccount['type']);
        $attributes = $createdAccount['attributes'];
        $this->assertEquals($expectedResult['name'], $attributes['name']);
        $this->assertEquals($expectedResult['status'], $attributes['status']);
    }

    public static function successCreateAccountProvider(): array
    {
        return [
            'Success, create account' => [
                [
                    'name' => 'testNameSuccess',
                ],
                [
                    'type' => 'account',
                    'name' => 'testNameSuccess',
                    'status' => 'New',
                ],
            ],
        ];

    }
}