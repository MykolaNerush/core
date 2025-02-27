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
//            'Wrong order' => [
//                null, // expected
//                'page=1&perPage=1&order=DESC_&sort=name', // filters
//                500,
//            ],
//            'Empty data, wrong page' => [
//                [], // expected
//                'page=111&perPage=11&order=DESC', // filters
//                200,
//            ],
            'Success, with one account' => [
                [
                    [
                        'id' => '95b716fb-dc27-4d9a-a64c-e45b3143fb25',
                        'type' => 'accounts',
                        'attributes' =>
                            [
                                'uuid' => '95b716fb-dc27-4d9a-a64c-e45b3143fb25',
                                'name' => 'FOR_LIST_ACCOUNTS',
                                'status' => 'Active',
                                'user' => [],
                                'timestamps' =>
                                    [
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