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
        $client = static::createAuthClient();

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
                400,
            ],
            'Empty data, wrong page' => [
                [], // expected
                'page=111&perPage=11&order=DESC', // filters
                200,
            ],
            'Success, with one account' => [
                [
                    array(
                        'id' => '1fcd19a8-49af-4ffd-abc7-e45b3143fb25',
                        'type' => 'accounts',
                        'attributes' =>
                            array(
                                'uuid' => '1fcd19a8-49af-4ffd-abc7-e45b3143fb25',
                                'name' => 'FOR_LIST_ACCOUNTS',
                                'status' => 'Active',
                                'user' =>
                                    array(
                                        'id' => '4c6a78c7-773c-4d2b-9399-02f6b35aa09a',
                                        'uuid' => '4c6a78c7-773c-4d2b-9399-02f6b35aa09a',
                                        'name' => 'Jane Doe',
                                        'email' => 'jane@example.com',
                                        'status' => 'Active',
                                        'account' =>
                                            array(
                                                'uuid' => '1fcd19a8-49af-4ffd-abc7-e45b3143fb25',
                                                'accountName' => 'FOR_LIST_ACCOUNTS',
                                                'balance' => 1500,
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
                                'timestamps' =>
                                    array(
                                        'createdAt' => '2025-01-05 16:44:16',
                                        'updatedAt' => NULL,
                                    ),
                            ),
                    ),
                ], // expected
                'perPage=11&order=DESC&sort=accountName&page=1&filter[uuid]=1fcd19a8-49af-4ffd-abc7-e45b3143fb25', // filters
                200,
            ],
        ];
    }
}