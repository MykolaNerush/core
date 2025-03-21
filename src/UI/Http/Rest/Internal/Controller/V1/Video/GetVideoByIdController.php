<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Internal\Controller\V1\Video;

use App\Domain\Core\Video\Entity\Video;
use App\Domain\Shared\Security\ResourceVoter;
use OpenApi\Attributes as OA;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Application\Query\Video\GetVideo\GetVideoQuery;
use App\UI\Http\Rest\Internal\Controller\QueryController;
use App\UI\Http\Rest\Internal\DTO\Video\GetVideoRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

#[OA\Tag(name: 'Video')]
final class GetVideoByIdController extends QueryController
{
    public string $dtoClass = GetVideoRequest::class;

    #[OA\Get(
        summary: 'Get video by UUID',
        security: [['Bearer' => []]],
        responses: [
            new OA\Response(response: Response::HTTP_OK, description: "Success"),
            new OA\Response(response: Response::HTTP_BAD_REQUEST, description: "Bad Request"),
            new OA\Response(response: Response::HTTP_UNAUTHORIZED, description: "Unauthorized"),
            new OA\Response(response: Response::HTTP_NOT_FOUND, description: "Conflict"),
            new OA\Response(response: Response::HTTP_INTERNAL_SERVER_ERROR, description: "Internal server Error"),
        ]
    )]
    #[OA\Parameter(
        name: 'uuid',
        description: 'Example: 6057dec5-0446-4117-a1e9-defc798d228d',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'string')
    )]
    public function __invoke(string $uuid, MessageBusInterface $messageBus): JsonResponse
    {
        $this->denyAccessUnlessGranted(ResourceVoter::VIEW, ['repo' => Video::class, 'uuid' => $uuid]);

        $query = new GetVideoQuery(uuid: Uuid::fromString($uuid));
        $envelope = $messageBus->dispatch($query);
        $handledStamp = $envelope->last(HandledStamp::class);
        if (!$handledStamp) {
            throw new \RuntimeException('No handler was found for this query or handler failed to execute.');
        }

        $video = $handledStamp->getResult();
        return $this->jsonItem($video);
    }
}
