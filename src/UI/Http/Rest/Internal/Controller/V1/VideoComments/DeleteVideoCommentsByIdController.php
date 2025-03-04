<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Internal\Controller\V1\VideoComments;

use App\Application\Command\VideoComments\Delete\DeleteVideoCommentsCommand;
use App\UI\Http\Rest\Internal\DTO\VideoComments\DeleteVideoCommentsRequest;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

#[OA\Tag(name: 'Video comments')]
final class DeleteVideoCommentsByIdController
{
    public string $dtoClass = DeleteVideoCommentsRequest::class;

    #[OA\Delete(
        summary: "Delete video comment by ID",
        security: [['Bearer' => []]],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "uuid",
                in: "path",
                schema: new OA\Schema(type: "string")
            )
        ],
        responses: [
            new OA\Response(response: Response::HTTP_OK, description: "Video comment deleted successfully"),
            new OA\Response(response: Response::HTTP_BAD_REQUEST, description: "Bad Request"),
            new OA\Response(response: Response::HTTP_INTERNAL_SERVER_ERROR, description: "Internal server Error")
        ]
    )]
    public function __invoke(string $uuid, MessageBusInterface $messageBus): JsonResponse
    {
        $command = new DeleteVideoCommentsCommand(uuid: Uuid::fromString($uuid));
        $envelope = $messageBus->dispatch($command);
        $handledStamp = $envelope->last(HandledStamp::class);
        if (!$handledStamp) {
            throw new \RuntimeException('No handler was found for this query or handler failed to execute.');
        }
        return new JsonResponse(null, Response::HTTP_OK);
    }
}
