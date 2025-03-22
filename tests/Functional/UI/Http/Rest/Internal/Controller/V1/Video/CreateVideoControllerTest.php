<?php

declare(strict_types=1);

namespace App\tests\Functional\UI\Http\Rest\Internal\Controller\V1\Video;

use App\Domain\Core\Video\Entity\Video;
use App\Infrastructure\Core\Video\Repository\VideoRepository;
use App\Tests\Functional\BaseTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Response;

class CreateVideoControllerTest extends BaseTestCase
{
    #[DataProvider('createVideosErrorProvider')]
    public function testCreateVideosError($params, $expectedResult): void
    {
        $client = static::createAuthClient();

        $client->request('POST', '/api/v1/internal/videos', $params);
        $actual = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals($expectedResult, $actual);
    }

    public static function createVideosErrorProvider(): array
    {
        return [
            'Error, required fields are missing.' => [
                [
                ],
                [
                    'status' => 'error',
                    'messages' =>
                        array(
                            0 => 'The title field is required.',
                            1 => 'The file field is required.',
                        ),
                    'code' => 400,
                ]
            ],
        ];

    }


    #[DataProvider('successCreateVideosProvider')]
    public function testSuccessCreateVideos($params): void
    {
        //todo add field file !!!!!
        $client = static::createAuthClient();

        $client->request('POST', '/api/v1/internal/videos', $params);
        json_decode($client->getResponse()->getContent(), true);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $videoRepository = self::getContainer()->get(VideoRepository::class);
        /* @var ?Video $createdVideo */
        $createdVideo = $videoRepository->findOneBy(['title' => $params['title'], 'description' => $params['description']]);
        $this->assertNotNull($createdVideo);
        $this->assertEquals($params['title'], $createdVideo->getTitle());
        $this->assertEquals($params['description'], $createdVideo->getDescription());
        $this->assertEquals($params['status'], $createdVideo->getStatus()->label());
    }

    public static function successCreateVideosProvider(): array
    {
        return [
            'Success, create video' => [
                [
                    'title' => 'testNameSuccessCreate',
                    'description' => 'description_success_create',
                    'status' => 'Draft',
                ],
            ],
        ];

    }
}