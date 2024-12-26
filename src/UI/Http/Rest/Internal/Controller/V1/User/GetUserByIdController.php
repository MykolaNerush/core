<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Internal\Controller\V1\User;

use App\Application\Query\Shared\Item;
use OpenApi\Attributes as OA;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Application\Query\User\GetUser\GetUserQuery;
use App\UI\Http\Rest\Internal\Controller\QueryController;
use App\UI\Http\Rest\Internal\DTO\Users\GetUserRequest;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

#[OA\Tag(name: 'User')]
final class GetUserByIdController extends QueryController
{
    public string $dtoClass = GetUserRequest::class;

    #[OA\Get(
        summary: 'Get user by UUID',
        responses: [
            new OA\Response(response: 200, description: 'Ok'),
            new OA\Response(response: 400, description: 'Bad request'),
            new OA\Response(response: 401, description: 'Unauthorized'),
            new OA\Response(response: 404, description: 'Not found'),
            new OA\Response(response: 500, description: 'Internal server error')
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
        $query = new GetUserQuery(uuid: Uuid::fromString($uuid));
        $envelope = $messageBus->dispatch($query);
        $handledStamp = $envelope->last(HandledStamp::class);
        if (!$handledStamp) {
            throw new \RuntimeException('No handler was found for this query or handler failed to execute.');
        }

        /** @var Item $user */
        $user = $handledStamp->getResult();
        return $this->json($user);
    }
}
