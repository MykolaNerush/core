<?php

declare(strict_types=1);

namespace App\tests\Functional\UI\Http\Rest\Internal\Controller\V1\User;

use App\Tests\Functional\BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Ramsey\Uuid\Guid\Guid;

class DeleteUserByIdControllerTest extends BaseTestCase
{
    #[DataProvider('deleteUsersErrorProvider')]
    public function testDeleteUserError($uuid, $expectedResult): void
    {
        $client = static::createAuthClient();

        $client->request('DELETE', '/api/v1/internal/users/' . $uuid);
        $actual = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals($expectedResult['status'], $actual['status']);
        $this->assertEquals($expectedResult['message'], $actual['message']);
        $this->assertResponseStatusCodeSame($expectedResult['code']);
    }

    public static function deleteUsersErrorProvider(): array
    {
        return [
            'Error, not valid fields.' => [
                'e5010159-f361-4ab4-b5a2-4557144e3f66',
                [
                    'status' => 'error',
                    'messages' => ['User not exists'],
                    'code' => 400,
                ]
            ],
        ];
    }

    #[DataProvider('successDeleteUsersProvider')]
    public function testSuccessDeleteUser($uuid, $expectedResult): void
    {
        $client = static::createAuthClient();

        $client->request('DELETE', '/api/v1/internal/users/' . $uuid);
        $this->assertResponseStatusCodeSame(200);
        $client->request('GET', '/api/v1/internal/users/' . $uuid);
        $actual = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals($expectedResult['status'], $actual['status']);
        $this->assertEquals($expectedResult['message'], $actual['message']);
        $this->assertResponseStatusCodeSame($expectedResult['code']);
    }

    public static function successDeleteUsersProvider(): array
    {
        return [
            'Success delete user' => [
                'e5010159-f361-4ab4-b5a2-4557144e3f12',
                [
                    'status' => 'error',
                    'messages' => ['User not found'],
                    'code' => 500,
                ]
            ],
        ];
    }
}