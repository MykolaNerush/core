<?php

declare(strict_types=1);

namespace App\tests\Functional\UI\Http\Rest\Internal\Controller\V1\Video;

use App\Tests\Functional\BaseTestCase;
use JetBrains\PhpStorm\NoReturn;
use PHPUnit\Framework\Attributes\DataProvider;
use Ramsey\Uuid\Guid\Guid;

class DeleteVideoByIdControllerTest extends BaseTestCase
{
    #[DataProvider('deleteVideosErrorProvider')]
    public function testDeleteVideoError($uuid, $expectedResult): void
    {
        $client = static::createClient();

        $client->request('DELETE', '/api/v1/internal/videos/' . $uuid);
        $actual = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals($expectedResult['status'], $actual['status']);
        $this->assertEquals($expectedResult['message'], $actual['message']);
        $this->assertResponseStatusCodeSame($expectedResult['code']);
    }

    public static function deleteVideosErrorProvider(): array
    {
        return [
            'Error, not valid fields.' => [
                'e5010159-f361-4ab4-b5a2-4557144e3f66',
                [
                    'status' => 'error',
                    'message' => 'Video not found',
                    'code' => 500,
                ]
            ],
        ];
    }

    #[NoReturn] #[DataProvider('successDeleteVideosProvider')]
    public function testSuccessDeleteVideo($uuid, $expectedResult): void
    {
        $client = static::createClient();

        $client->request('DELETE', '/api/v1/internal/videos/' . $uuid);
        $this->assertResponseStatusCodeSame(200);
        $client->request('GET', '/api/v1/internal/videos/' . $uuid);
        $actual = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals($expectedResult['status'], $actual['status']);
        $this->assertEquals($expectedResult['message'], $actual['message']);
        $this->assertResponseStatusCodeSame($expectedResult['code']);
    }

    public static function successDeleteVideosProvider(): array
    {
        return [
            'Success delete video' => [
                '8027f5f2-330f-4ea3-abcd-aeb4e1d3ea0e',
                [
                    'status' => 'error',
                    'message' => 'Video not found',
                    'code' => 500,
                ]
            ],
        ];
    }
}