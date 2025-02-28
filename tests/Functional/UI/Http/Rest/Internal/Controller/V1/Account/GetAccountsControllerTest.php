<?php

declare(strict_types=1);

namespace App\tests\Functional\UI\Http\Rest\Internal\Controller\V1\Account;

use App\Tests\Functional\BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class GetAccountsControllerTest extends BaseTestCase
{
    #[DataProvider('additionProvider')]
    public function testGetAccounts($expectedResult, $filter, $expectedCode): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/v1/internal/accounts?' . $filter);
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
                'page=111&perPage=11&order=DESC', // filters
                200,
            ],
            'Success, with one account' => [
                [
                    [
                        'id' => '95b716fb-dc27-4d9a-a64c-e45b3143fb25',
                        'type' => 'accounts',
                        'attributes' => [
                            'uuid' => '95b716fb-dc27-4d9a-a64c-e45b3143fb25',
                            'name' => 'FOR_LIST_ACCOUNTS',
                            'status' => 'Active',
                            'user' => [
                                'id' => '4c6a78c7-773c-4d2b-9399-02f6b35aa09a',
                                'uuid' => '4c6a78c7-773c-4d2b-9399-02f6b35aa09a',
                                'name' => 'Jane Doe',
                                'email' => 'jane@example.com',
                                'status' => 'Active',
                                'account' => [
                                    [
                                        'uuid' => '2cd95d63-73da-424f-82e4-283f2cd05cd6',
                                        'accountName' => 'FOR_UPDATE_ACCOUNTS',
                                        'balance' => 1500,
                                        'createdAt' => [
                                            'date' => '2025-01-05 16:44:16.000000',
                                            'timezone_type' => 3,
                                            'timezone' => 'UTC',
                                        ],
                                        'updatedAt' => NULL,
                                        'deletedAt' => NULL,
                                        'status' => 'active',
                                    ],
                                    [
                                        'uuid' => '95b716fb-dc27-4d9a-a64c-e45b3143fb25',
                                        'accountName' => 'FOR_LIST_ACCOUNTS',
                                        'balance' => 1500,
                                        'createdAt' => [
                                            'date' => '2025-01-05 16:44:16.000000',
                                            'timezone_type' => 3,
                                            'timezone' => 'UTC',
                                        ],
                                        'updatedAt' => NULL,
                                        'deletedAt' => NULL,
                                        'status' => 'active',
                                    ],
                                ],
                                'timestamps' => [
                                    'createdAt' => '2025-01-05 16:44:16',
                                    'updatedAt' => NULL,
                                ],
                            ],
                            'timestamps' => [
                                'createdAt' => '2025-01-05 16:44:16',
                                'updatedAt' => NULL,
                            ],
                        ],
                    ],
                ], // expected
                'perPage=11&order=DESC&sort=accountName&page=1&filter[uuid]=95b716fb-dc27-4d9a-a64c-e45b3143fb25', // filters
                200,
            ],
        ];
    }
}