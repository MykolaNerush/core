<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Internal\Controller\V1\User;

use App\Application\Query\Shared\Collection;
use App\Application\Query\User\GetUsers\GetUsersQuery;
use App\Domain\Core\User\Entity\User;
use App\UI\Http\Rest\Internal\Controller\QueryController;
use App\UI\Http\Rest\Internal\DTO\Users\GetUsersRequest;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

#[OA\Tag(name: 'User')]
final class GetUsersController extends QueryController
{
    public string $dtoClass = GetUsersRequest::class;

    #[OA\Get(
        summary: 'Get users',
        security: [['Bearer' => []]],
        responses: [
            new OA\Response(response: Response::HTTP_OK, description: "User updated successfully"),
            new OA\Response(response: Response::HTTP_BAD_REQUEST, description: "Bad Request"),
            new OA\Response(response: Response::HTTP_UNAUTHORIZED, description: "Unauthorized"),
            new OA\Response(response: Response::HTTP_NOT_FOUND, description: "Conflict"),
            new OA\Response(response: Response::HTTP_INTERNAL_SERVER_ERROR, description: "Internal server Error"),
        ]
    )]
    #[OA\Parameter(
        name: 'page',
        description: 'Example: 5',
        in: 'query',
        required: false,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Parameter(
        name: 'perPage',
        description: 'Example: 10',
        in: 'query',
        required: false,
        schema: new OA\Schema(type: 'integer')
    )]
    #[OA\Parameter(
        name: 'order',
        description: 'Possible options: ASC, DESC',
        in: 'query',
        required: false,
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'sort',
        description: 'Possible options: name, status, updatedAt, createdAt',
        in: 'query',
        required: false,
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'filter[uuid]',
        description: 'Example: 6057dec5-0446-4117-a1e9-defc798d228d',
        in: 'query',
        required: false,
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'filter[email]',
        description: 'Example: some-string-as-a-part-of-email',
        in: 'query',
        required: false,
        schema: new OA\Schema(type: 'string')
    )]
    public function __invoke(Request $request, MessageBusInterface $messageBus): JsonResponse
    {
        /** @var string $order */
        $order = $request->get('order', 'ASC');
        /** @var string $sort */
        $sort = $request->get('sort', 'createdAt');
        /** @var array<string, mixed> $filter */
        $filter = $request->get('filter', []);
        /** @var ?string $uuid */
        $uuid = $filter['uuid'] ?? null;
        /** @var ?string $emailSearch */
        $emailSearch = $filter['email'] ?? null;
        /** @var int $page */
        $page = $request->get('page', 1);
        /** @var int $perPage */
        $perPage = $request->get('perPage', 10);

        $query = new GetUsersQuery(
            routeGenerator: $this->routeWithPageAsCallable($request),
            page: (int)$page,
            perPage: (int)$perPage,
            order: $order,
            sort: $sort,
            uuid: $uuid,
            emailSearch: $emailSearch,
        );
        $envelope = $messageBus->dispatch($query);
        $handledStamp = $envelope->last(HandledStamp::class);
        if (!$handledStamp) {
            throw new \RuntimeException('No handler was found for this query or handler failed to execute.');
        }
        /** @var Collection<array<int, array<string, mixed>>> $users */
        $users = $handledStamp->getResult();
        return $this->jsonCollection($users);

    }
}
