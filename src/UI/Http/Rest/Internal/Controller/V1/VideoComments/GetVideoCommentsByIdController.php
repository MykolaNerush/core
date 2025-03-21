<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Internal\Controller\V1\VideoComments;

use App\Application\Query\VideoComments\GetVideoComments\GetVideoCommentsQuery;
use App\UI\Http\Rest\Internal\DTO\VideoComments\GetVideoCommentRequest;
use OpenApi\Attributes as OA;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\UI\Http\Rest\Internal\Controller\QueryController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

#[OA\Tag(name: 'Video comments')]
final class GetVideoCommentsByIdController extends QueryController
{
    public string $dtoClass = GetVideoCommentRequest::class;

    #[OA\Get(
        summary: 'Get video comments by UUID',
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
        $query = new GetVideoCommentsQuery(uuid: Uuid::fromString($uuid));
        $envelope = $messageBus->dispatch($query);
        $handledStamp = $envelope->last(HandledStamp::class);
        if (!$handledStamp) {
            throw new \RuntimeException('No handler was found for this query or handler failed to execute.');
        }

        $video = $handledStamp->getResult();
        return $this->jsonItem($video);
    }
}
