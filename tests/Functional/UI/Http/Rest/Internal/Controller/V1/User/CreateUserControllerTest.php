<?php

declare(strict_types=1);

namespace App\tests\Functional\UI\Http\Rest\Internal\Controller\V1\User;

use App\Tests\Functional\BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Ramsey\Uuid\Guid\Guid;

class CreateUserControllerTest extends BaseTestCase
{
    #[DataProvider('createUsersErrorProvider')]
    public function testCreateUsersError($params, $expectedResult): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/v1/internal/users', $params);
        $actual = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals($expectedResult, $actual);
    }

    public static function createUsersErrorProvider(): array
    {
        return [
            'Error, required fields are missing.' => [
                [
                ],
                [
                    'status' => 'error',
                    'message' => 'The name field is required., The email field is required., The password field is required.',
                    'code' => 500,
                ]
            ],
            'Error, Email already exists.' => [
                [
                    'name' => 'testNameErrorEmail',
                    'email' => 'test@gmail.com',
                    'password' => 'password',
                ],
                [
                    'status' => 'error',
                    'message' => 'Email already exists.',
                    'code' => 500,
                ]
            ],
        ];

    }


    #[DataProvider('successCreateUsersProvider')]
    public function testSuccessCreateUsers($params, $expectedResult): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/v1/internal/users', $params);
        $createdUserId = json_decode($client->getResponse()->getContent(), true);

        $this->assertTrue(
            Guid::isValid($createdUserId['id']),
            'The returned id is not a valid UUID.'
        );
        $client->request('GET', '/api/v1/internal/users/' . $createdUserId['id']);
        $createdUser = json_decode($client->getResponse()->getContent(), true)['data'];
        $this->assertResponseStatusCodeSame(200);
        $this->assertEquals($expectedResult['type'], $createdUser['type']);
        $attributes = $createdUser['attributes'];
        $this->assertEquals($expectedResult['name'], $attributes['name']);
        $this->assertEquals($expectedResult['email'], $attributes['email']);
        $this->assertEquals($expectedResult['status'], $attributes['status']);
    }

    public static function successCreateUsersProvider(): array
    {
        return [
            'Success, create user' => [
                [
                    'name' => 'testNameSuccess',
                    'email' => 'test_createw@gmail.com',
                    'password' => 'password_create',
                ],
                [
                    'type' => 'user',
                    'name' => 'testNameSuccess',
                    'email' => 'test_createw@gmail.com',
                    'status' => 'New',
                ],
            ],
        ];

    }
}