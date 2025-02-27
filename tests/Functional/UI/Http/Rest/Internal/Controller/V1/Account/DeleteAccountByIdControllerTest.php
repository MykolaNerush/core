<?php

declare(strict_types=1);

namespace App\tests\Functional\UI\Http\Rest\Internal\Controller\V1\Account;

use App\Tests\Functional\BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Ramsey\Uuid\Guid\Guid;

class DeleteAccountByIdControllerTest extends BaseTestCase
{
    #[DataProvider('deleteAccountsErrorProvider')]
    public function testDeleteAccountError($uuid, $expectedResult): void
    {
        $client = static::createClient();

        $client->request('DELETE', '/api/v1/internal/accounts/' . $uuid);
        $actual = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals($expectedResult['status'], $actual['status']);
        $this->assertEquals($expectedResult['message'], $actual['message']);
        $this->assertResponseStatusCodeSame($expectedResult['code']);
    }

    public static function deleteAccountsErrorProvider(): array
    {
        return [
            'Error, not valid fields.' => [
                'e5010159-f361-4ab4-b5a2-4557144e3f66',
                [
                    'status' => 'error',
                    'message' => 'Account not found',
                    'code' => 500,
                ]
            ],
        ];
    }

    #[DataProvider('successDeleteAccountsProvider')]
    public function testSuccessDeleteAccount($uuid, $expectedResult): void
    {
        $client = static::createClient();

        $client->request('DELETE', '/api/v1/internal/accounts/' . $uuid);
        $this->assertResponseStatusCodeSame(200);
        $client->request('GET', '/api/v1/internal/accounts/' . $uuid);
        $actual = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals($expectedResult['status'], $actual['status']);
        $this->assertEquals($expectedResult['message'], $actual['message']);
        $this->assertResponseStatusCodeSame($expectedResult['code']);
    }

    public static function successDeleteAccountsProvider(): array
    {
        return [
            'Success delete account' => [
                '1fcd19a8-49af-00fd-abc7-000000000000',
                [
                    'status' => 'error',
                    'message' => 'Account not found',
                    'code' => 500,
                ]
            ],
        ];
    }
}