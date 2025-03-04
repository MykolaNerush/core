<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Internal\Controller\V1\VideoComment;

use App\Application\Command\Video\Create\CreateVideoCommand;
use App\UI\Http\Rest\Internal\Controller\CommandController;
use App\UI\Http\Rest\Internal\DTO\Video\CreateVideoRequest;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

#[OA\Tag(name: 'Video')]
final class CreateVideoCommentController extends CommandController
{
    public string $dtoClass = CreateVideoRequest::class;

    #[OA\Post(
        summary: 'Create video',
        security: [['Bearer' => []]],
        responses: [
            new OA\Response(response: Response::HTTP_CREATED, description: 'video created successfully'),
            new OA\Response(response: Response::HTTP_BAD_REQUEST, description: 'Bad request'),
            new OA\Response(response: Response::HTTP_CONFLICT, description: 'Conflict'),
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
        name: 'filePath',
        description: 'filePath',
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
        $filePath = $request->get('filePath');
        $thumbnailPath = $request->get('thumbnailPath');
        $duration = $request->get('duration', 0);
        $command = new CreateVideoCommand(
            title: $title,
            description: $description,
            filePath: $filePath,
            thumbnailPath: $thumbnailPath,
            duration: $duration,
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
