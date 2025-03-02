<?php

declare(strict_types=1);

namespace App\tests\Functional\UI\Http\Rest\Internal\Controller\V1\Video;

use App\Domain\Core\Video\Entity\Video;
use App\Infrastructure\Core\Video\Repository\VideoRepository;
use App\Tests\Functional\BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Ramsey\Uuid\Guid\Guid;
use Symfony\Component\HttpFoundation\Response;

class UpdateVideoByIdControllerTest extends BaseTestCase
{
    #[DataProvider('updateVideosErrorProvider')]
    public function testUpdateVideoError($uuid, $params, $expectedResult): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/v1/internal/videos/' . $uuid, $params);
        $actual = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals($expectedResult, $actual);
    }

    public static function updateVideosErrorProvider(): array
    {
        return [
            'Error, not valid fields.' => [
                'e5010159-f361-4ab4-b5a2-4557144e3f11',
                [
                    'title' => 'n',
                ],
                [
                    'status' => 'error',
                    'message' => 'The title must be at least 2 characters long.',
                    'code' => 500,
                ]
            ],
        ];
    }

    #[DataProvider('successUpdateVideosProvider')]
    public function testSuccessUpdateVideo($uuid, $params): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/v1/internal/videos/' . $uuid, $params);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $videoRepository = self::getContainer()->get(VideoRepository::class);
        /* @var ?Video $updatedVideo */
        $updatedVideo = $videoRepository->findOneBy(['title' => $params['title']]);
        $this->assertNotNull($updatedVideo);
        $this->assertEquals($params['title'], $updatedVideo->getTitle());
    }

    public static function successUpdateVideosProvider(): array
    {
        return [
            'Success update video' => [
                '8027f5f2-330f-4ea3-abcd-aeb4e1d3ea03',
                [
                    'title' => 'Test_update_1',
                ],
            ],
        ];
    }
}