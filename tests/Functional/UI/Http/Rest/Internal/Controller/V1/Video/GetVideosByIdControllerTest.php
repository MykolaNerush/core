<?php

declare(strict_types=1);

namespace App\tests\Functional\UI\Http\Rest\Internal\Controller\V1\Video;

use App\Tests\Functional\BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class GetVideosByIdControllerTest extends BaseTestCase
{
    #[DataProvider('additionProvider')]
    public function testGetVideo($uuid, $expectedResult, $expectedCode): void
    {
        $client = static::createClient();
        $client->request('GET', '/videos');
        $client->request('GET', '/api/v1/internal/videos/' . $uuid);
        $actualVideo = json_decode($client->getResponse()->getContent(), true);

        if ($actualVideo['status'] == 'error') {
            $this->assertEquals($expectedResult['message'], $actualVideo['message']);
        } else {
            $attributes = $actualVideo['data']['attributes'];
            $this->assertEquals($uuid, $attributes['uuid']);
            $this->assertEquals($expectedResult['name'], $attributes['name']);
            $this->assertEquals($expectedResult['email'], $attributes['email']);
        }
        $this->assertResponseStatusCodeSame($expectedCode);
    }

    public static function additionProvider(): array
    {
        return [
            'Success get video' => [
                '8027f5f2-330f-4ea3-abcd-aeb4e1d3ea01',
                [
                    'title' => 'title_get_success',
                    'description' => 'desc_get_success',
                ],
                200,
            ],
            'Error, video not found' => [
                '1fcd19a8-49af-44fd-abc7-560f8b415814',
                [
                    'message' => 'Video not found',
                ],
                500,
            ],
        ];

    }
}