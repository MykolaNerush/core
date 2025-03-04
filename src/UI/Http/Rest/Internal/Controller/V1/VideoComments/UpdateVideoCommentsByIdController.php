<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Internal\Controller\V1\VideoComments;

use App\Application\Command\VideoComments\Update\UpdateVideoCommentsCommand;
use App\UI\Http\Rest\Internal\DTO\Video\UpdateVideoRequest;
use OpenApi\Attributes as OA;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

#[OA\Tag(name: 'Video comments')]
final class UpdateVideoCommentsByIdController
{
    public string $dtoClass = UpdateVideoRequest::class;

    #[OA\Post(
        summary: "Update video by ID",
        security: [['Bearer' => []]],
        requestBody: new OA\RequestBody(
            description: "Video command",
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "comment", type: "comment"),
                ]
            )
        ),
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "uuid",
                in: "path",
                schema: new OA\Schema(type: "string")
            )
        ],
        responses: [
            new OA\Response(response: Response::HTTP_OK, description: "Video updated successfully"),
            new OA\Response(response: Response::HTTP_BAD_REQUEST, description: "Bad Request"),
            new OA\Response(response: Response::HTTP_UNAUTHORIZED, description: "Unauthorized"),
            new OA\Response(response: Response::HTTP_CONFLICT, description: "Conflict"),
            new OA\Response(response: Response::HTTP_INTERNAL_SERVER_ERROR, description: "Internal server Error")
        ]
    )]
    public function __invoke(string $uuid, Request $request, MessageBusInterface $messageBus): JsonResponse
    {
        $command = new UpdateVideoCommentsCommand(
            currentUuid: Uuid::fromString($uuid),
            comment: $request->get('comment'),
        );
        $envelope = $messageBus->dispatch($command);
        $handledStamp = $envelope->last(HandledStamp::class);
        if (!$handledStamp) {
            throw new \RuntimeException('No handler was found for this query or handler failed to execute.');
        }
        return new JsonResponse(null, Response::HTTP_OK);
    }
}
