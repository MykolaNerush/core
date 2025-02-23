<?php

declare(strict_types=1);

namespace App\tests\Functional\UI\Http\Rest\Internal\Controller\V1\User;

use App\Tests\Functional\BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Ramsey\Uuid\Guid\Guid;

class UpdateUserByIdControllerTest extends BaseTestCase
{
    #[DataProvider('updateUsersErrorProvider')]
    public function testUpdateUserError($uuid, $params, $expectedResult): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/v1/internal/users/' . $uuid, $params);
        $actual = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals($expectedResult, $actual);
    }

    public static function updateUsersErrorProvider(): array
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
                    'message' => 'The name must be at least 2 characters long., The email format is invalid.',
                    'code' => 500,
                ]
            ],
        ];
    }

    #[DataProvider('successUpdateUsersProvider')]
    public function testSuccessUpdateUser($uuid, $params): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/v1/internal/users/' . $uuid, $params);
        $this->assertResponseStatusCodeSame(200);

        $client->request('GET', '/api/v1/internal/users/' . $uuid);
        $this->assertResponseStatusCodeSame(200);
        $createdUser = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals($params['name'], $createdUser['data']['attributes']['name']);
    }

    public static function successUpdateUsersProvider(): array
    {
        return [
            'Success update user' => [
                'e5010159-f361-4ab4-b5a2-4557144e3f11',
                [
                    'name' => 'Test_update_1',
                ],
            ],
        ];
    }
}