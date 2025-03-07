<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Internal\Controller\V1\Video;

use App\Application\Command\Video\Create\CreateVideoCommand;
use App\UI\Http\Rest\Internal\Controller\CommandController;
use App\UI\Http\Rest\Internal\DTO\Video\CreateVideoRequest;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[OA\Tag(name: 'Video')]
final class CreateVideoController extends CommandController
{
    public string $dtoClass = CreateVideoRequest::class;

    #[OA\Post(
        summary: 'Create video',
        security: [['Bearer' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: 'title', type: 'string'),
                        new OA\Property(property: 'description', type: 'string'),
                        new OA\Property(property: 'file', type: 'string', format: 'binary'),
                        new OA\Property(property: 'thumbnail', type: 'string', format: 'binary'),
                        new OA\Property(property: 'duration', type: 'integer'),
                    ],
                    type: 'object'
                )
            )
        ),
        responses: [
            new OA\Response(response: Response::HTTP_CREATED, description: 'Video created successfully'),
            new OA\Response(response: Response::HTTP_BAD_REQUEST, description: 'Bad request'),
            new OA\Response(response: Response::HTTP_INTERNAL_SERVER_ERROR, description: 'Internal server error')
        ]
    )]
    #[OA\Parameter(
        name: 'title',
        description: 'Title',
        in: 'query',
        required: true,
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'description',
        description: 'Description',
        in: 'query',
        required: false,
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'thumbnailPath',
        description: 'thumbnailPath',
        in: 'query',
        required: false,
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'duration',
        description: 'duration',
        in: 'query',
        required: false,
        schema: new OA\Schema(type: 'integer')
    )]
    public function __invoke(Request $request, MessageBusInterface $messageBus): JsonResponse
    {
        $title = $request->get('title');
        $description = $request->get('description');
        $thumbnailPath = $request->get('thumbnailPath');
        $duration = $request->get('duration', 0);
        /** @var UploadedFile|null $videoFile */
        $videoFile = $request->files->get('file');
        $command = new CreateVideoCommand(
            title: $title,
            description: $description,
            thumbnailPath: $thumbnailPath,
            videoFile: $videoFile,
            duration: $duration, //todo get from file
        );
        $envelope = $messageBus->dispatch($command);
        $handledStamp = $envelope->last(HandledStamp::class);
        if (!$handledStamp) {
            throw new \RuntimeException('No handler was found for this query or handler failed to execute.');
        }
        $videoId = $handledStamp->getResult();
        return new JsonResponse(['id' => $videoId], Response::HTTP_CREATED);
    }
}
