<?php

declare(strict_types=1);

namespace App\tests\Functional\UI\Http\Rest\Internal\Controller\V1\User;

use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GetUsersControllerTest extends WebTestCase
{
    #[DataProvider('additionProvider')]
    public function testGetUsers($expectedResult, $filter, $expectedCode): void
    {
        $client = static::createClient();

        $client->request('GET', '/users');
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
                        'id' => '2fafdcd9-ecc5-4e41-bf5d-f29fe1971b5b',
                        'type' => 'users',
                        'attributes' =>
                            [
                                'uuid' => '2fafdcd9-ecc5-4e41-bf5d-f29fe1971b5b',
                                'name' => 'Test',
                                'email' => 'test@gmail.com',
                                'status' => 'Active',
                                'account' =>
                                    [
                                        [
                                            'uuid' => 'f8540a38-dc5d-4c92-82a7-3284a61c1f68',
                                            'accountName' => 'Main Account John',
                                            'balance' => 1000,
                                            'createdAt' =>
                                                [
                                                    'date' => '2025-01-05 17:13:23.000000',
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
                                        'createdAt' => '2025-01-05 17:13:23',
                                        'updatedAt' => NULL,
                                    ],
                            ],
                    ],
                ], // expected
                'page=1&perPage=1&order=DESC&sort=name', // filters
                200,
            ],
        ];

    }
}