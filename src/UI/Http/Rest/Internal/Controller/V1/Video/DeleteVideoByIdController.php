<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Internal\Controller\V1\Video;

use App\Application\Command\Video\Delete\DeleteVideoCommand;
use App\Domain\Core\Video\Entity\Video;
use App\Domain\Shared\Security\ResourceVoter;
use App\UI\Http\Rest\Internal\Controller\QueryController;
use App\UI\Http\Rest\Internal\DTO\Video\DeleteVideoRequest;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

#[OA\Tag(name: 'Video')]
final class DeleteVideoByIdController extends QueryController
{
    public string $dtoClass = DeleteVideoRequest::class;

    #[OA\Delete(
        summary: "Delete video by ID",
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
            new OA\Response(response: Response::HTTP_OK, description: "Video deleted successfully"),
            new OA\Response(response: Response::HTTP_BAD_REQUEST, description: "Bad Request"),
            new OA\Response(response: Response::HTTP_INTERNAL_SERVER_ERROR, description: "Internal server Error")
        ]
    )]
    public function __invoke(string $uuid, MessageBusInterface $messageBus): JsonResponse
    {
        $this->denyAccessUnlessGranted(ResourceVoter::VIEW, ['repo' => Video::class, 'uuid' => $uuid]);
        $command = new DeleteVideoCommand(uuid: Uuid::fromString($uuid));
        $envelope = $messageBus->dispatch($command);
        $handledStamp = $envelope->last(HandledStamp::class);
        if (!$handledStamp) {
            throw new \RuntimeException('No handler was found for this query or handler failed to execute.');
        }
        return new JsonResponse(null, Response::HTTP_OK);
    }
}
