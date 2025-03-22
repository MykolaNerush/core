<?php

declare(strict_types=1);

namespace App\Tests\Functional\UI\Http\Rest\Internal\Controller\V1\User;

use App\Tests\Functional\BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class GetUsersControllerTest extends BaseTestCase
{
    #[DataProvider('additionProvider')]
    public function testGetUsers($expectedResult, $filter, $expectedCode): void
    {
        $client = static::createAuthClient();

        $client->request('GET', '/api/v1/internal/users?' . $filter);
        $actual = json_decode($client->getResponse()->getContent(), true)['data'];
        $this->assertEquals($expectedResult, $actual);
        $this->assertResponseStatusCodeSame($expectedCode);
    }


    public static function additionProvider(): array
    {
        return [
            'Wrong order' => [
                null, // expected
                'page=1&perPage=1&order=DESC_&sort=name', // filters
                400,
            ],
            'Empty data, wrong page' => [
                [], // expected
                'page=111&perPage=11&order=DESC&sort=name', // filters
                200,
            ],
            'Success, with one user' => [
                array(
                    0 =>
                        array(
                            'id' => '4bde8464-6fc3-4904-9095-b89305efa532',
                            'type' => 'users',
                            'attributes' =>
                                array(
                                    'uuid' => '4bde8464-6fc3-4904-9095-b89305efa532',
                                    'roles' =>
                                        array(
                                            0 => 'ROLE_USER',
                                        ),
                                    'videos' =>
                                        array(),
                                    'name' => 'Test',
                                    'email' => 'test@gmail.com',
                                    'status' => 'Active',
                                    'account' =>
                                        array(
                                            'uuid' => '1fcd19a8-49af-4ffd-abc7-000000000000',
                                            'accountName' => 'FOR_DELETE',
                                            'balance' => 1000,
                                            'createdAt' =>
                                                array(
                                                    'date' => '2025-01-05 16:44:16.000000',
                                                    'timezone_type' => 3,
                                                    'timezone' => 'UTC',
                                                ),
                                            'updatedAt' => NULL,
                                            'deletedAt' => NULL,
                                            'status' => 'active',
                                        ),
                                    'timestamps' =>
                                        array(
                                            'createdAt' => '2025-01-05 16:44:16',
                                            'updatedAt' => NULL,
                                        ),
                                ),
                        ),
                ), // expected
                'page=1&perPage=1&order=DESC&sort=name&filter[uuid]=4bde8464-6fc3-4904-9095-b89305efa532', // filters
                200,
            ],
        ];
    }
}