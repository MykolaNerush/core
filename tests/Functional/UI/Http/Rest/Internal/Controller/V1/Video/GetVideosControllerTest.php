<?php

declare(strict_types=1);

namespace App\tests\Functional\UI\Http\Rest\Internal\Controller\V1\Video;

use App\Tests\Functional\BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class GetVideosControllerTest extends BaseTestCase
{
    #[DataProvider('additionProvider')]
    public function testGetVideos($expectedResult, $filter, $expectedCode): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/v1/internal/videos?' . $filter);
        $actual = json_decode($client->getResponse()->getContent(), true)['data'];
        $this->assertEquals($expectedResult, $actual);
        $this->assertResponseStatusCodeSame($expectedCode);
    }


    public static function additionProvider(): array
    {
        return [
            'Wrong order' => [
                null, // expected
                'page=1&perPage=1&order=DESC_&sort=title', // filters
                500,
            ],
            'Empty data, wrong page' => [
                [], // expected
                'page=111&perPage=11&order=DESC&sort=title', // filters
                200,
            ],
            'Success, with one video' => [
                [
                    [
                        'id' => '8027f5f2-330f-4ea3-abcd-aeb4e1d3ea02',
                        'type' => 'videos',
                        'attributes' =>
                            [
                                'uuid' => '8027f5f2-330f-4ea3-abcd-aeb4e1d3ea02',
                                'title' => 'title_get_list_success',
                                'description' => 'desc_get_list_success',
                                'filePath' => NULL,
                                'thumbnailPath' => NULL,
                                'getDuration' => 0,
                                'duration' => 0,
                                'status' => 'Draft',
                                'timestamps' =>
                                    [
                                        'createdAt' => '-0001-11-30 00:00:00',
                                        'updatedAt' => NULL,
                                    ],
                            ],
                    ],
                ], // expected
                'page=1&perPage=1&order=DESC&sort=title&filter[uuid]=8027f5f2-330f-4ea3-abcd-aeb4e1d3ea02', // filters
                200,
            ],
        ];
    }
}