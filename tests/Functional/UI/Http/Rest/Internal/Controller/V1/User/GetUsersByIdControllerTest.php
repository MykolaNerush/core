<?php

declare(strict_types=1);

namespace App\tests\Functional\UI\Http\Rest\Internal\Controller\V1\User;

use App\Tests\Functional\BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class GetUsersByIdControllerTest extends BaseTestCase
{
    #[DataProvider('additionProvider')]
    public function testGetUser($uuid, $expectedResult, $expectedCode): void
    {
        $client = static::createAuthClient();
        $client->request('GET', '/users');
        $client->request('GET', '/api/v1/internal/users/' . $uuid);
        $actualUser = json_decode($client->getResponse()->getContent(), true);

        if ($actualUser['status'] == 'error') {
            $this->assertEquals($expectedResult['message'], $actualUser['message']);
        } else {
            $attributes = $actualUser['data']['attributes'];
            $this->assertEquals($uuid, $attributes['uuid']);
            $this->assertEquals($expectedResult['name'], $attributes['name']);
            $this->assertEquals($expectedResult['email'], $attributes['email']);
        }
        $this->assertResponseStatusCodeSame($expectedCode);
    }

    public static function additionProvider(): array
    {
        return [
            'Success get user' => [
                '1fcd19a8-49af-44fd-abc7-560f8b41581e',
                [
                    'name' => 'name1',
                    'email' => 'test@gmail.com12',
                ],
                200,
            ],
            'Error, user not found' => [
                '1fcd19a8-49af-44fd-abc7-560f8b415814',
                [
                    'messages' => ['User not found'],
                ],
                500,
            ],
        ];

    }
}