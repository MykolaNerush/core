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
        $client = static::createClient();

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
                500,
            ],
            'Empty data, wrong page' => [
                [], // expected
                'page=111&perPage=11&order=DESC&sort=name', // filters
                200,
            ],
            'Success, with one user' => [
                [
                    [
                        'id' => '4bde8464-6fc3-4904-9095-b89305efa532',
                        'type' => 'users',
                        'attributes' =>
                            [
                                'uuid' => '4bde8464-6fc3-4904-9095-b89305efa532',
                                'name' => 'Test',
                                'email' => 'test@gmail.com',
                                'status' => 'Active',
                                'account' =>
                                    [
                                        [
                                            'uuid' => '1fcd19a8-49af-00fd-abc7-000000000000',
                                            'accountName' => 'FOR_DELETE',
                                            'balance' => 1000,
                                            'createdAt' =>
                                                [
                                                    'date' => '2025-01-05 16:44:16.000000',
                                                    'timezone_type' => 3,
                                                    'timezone' => 'UTC',
                                                ],
                                            'updatedAt' => NULL,
                                            'deletedAt' => NULL,
                                            'status' => 'active',
                                        ],
                                    ],
                                'timestamps' =>
                                    [
                                        'createdAt' => '2025-01-05 16:44:16',
                                        'updatedAt' => NULL,
                                    ],
                            ],
                    ],
                ], // expected
                'page=1&perPage=1&order=DESC&sort=name&filter[uuid]=4bde8464-6fc3-4904-9095-b89305efa532', // filters
                200,
            ],
        ];
    }
}