<?php

declare(strict_types=1);

namespace App\tests\Functional\UI\Http\Rest\Internal\Controller\V1\Account;

use App\Tests\Functional\BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class GetAccountByIdControllerTest extends BaseTestCase
{
    #[DataProvider('additionProvider')]
    public function testGetAccount($uuid, $expectedResult, $expectedCode): void
    {
        $client = static::createAuthClient();
        $client->request('GET', '/accounts');
        $client->request('GET', '/api/v1/internal/accounts/' . $uuid);
        $actualAccount = json_decode($client->getResponse()->getContent(), true);

        if ($actualAccount['status'] == 'error') {
            $this->assertEquals($expectedResult['message'], $actualAccount['message']);
        } else {
            $attributes = $actualAccount['data']['attributes'];
            $this->assertEquals($uuid, $attributes['uuid']);
            $this->assertEquals($expectedResult['name'], $attributes['name']);
            $this->assertEquals($expectedResult['email'], $attributes['email']);
        }
        $this->assertResponseStatusCodeSame($expectedCode);
    }

    public static function additionProvider(): array
    {
        return [
            'Success get account' => [
                '1fcd19a8-49af-4ffd-abc7-000000000001',
                [
                    'name' => 'FOR_FIND_BY_UD',
                ],
                200,
            ],
            'Error, account not found' => [
                '1fcd19a8-49af-44fd-abc7-560f8b415814',
                [
                    'messages' => ['Account not found'],
                ],
                500,
            ],
        ];

    }
}