<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Internal\Controller\V1\Account;

use App\Application\Query\Account\GetAccounts\GetAccountsQuery;
use App\UI\Http\Rest\Internal\Controller\QueryController;
use App\UI\Http\Rest\Internal\DTO\Account\GetAccountsRequest;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[OA\Tag(name: 'Account')]
#[IsGranted('ROLE_ADMIN')]
final class GetAccountsController extends QueryController
{
    public string $dtoClass = GetAccountsRequest::class;

    #[OA\Get(
        summary: 'Get accounts',
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
        name: 'filter[user]',
        description: 'Example: some-string-as-a-part-of-user',
        in: 'query',
        required: false,
        schema: new OA\Schema(type: 'string')
    )]
    public function __invoke(Request $request, MessageBusInterface $messageBus): JsonResponse
    {
        $order = $request->get('order', 'ASC');
        $sort = $request->get('sort', 'createdAt');
        $filter = $request->get('filter', []);
        $uuid = !empty($filter['uuid']) ? $this->getUuidOrNull($filter['uuid']) : null;
        $user = !empty($filter['user']) ? $this->getUuidOrNull($filter['user']) : null;
        $page = $request->get('page', 1);
        $perPage = $request->get('perPage', 10);

        $query = new GetAccountsQuery(
            routeGenerator: $this->routeWithPageAsCallable($request),
            page: (int)$page,
            perPage: (int)$perPage,
            order: $order,
            sort: $sort,
            uuid: $uuid,
            user: $user,
        );
        $envelope = $messageBus->dispatch($query);
        $handledStamp = $envelope->last(HandledStamp::class);
        if (!$handledStamp) {
            throw new \RuntimeException('No handler was found for this query or handler failed to execute.');
        }
        $accounts = $handledStamp->getResult();
        return $this->jsonCollection($accounts);

    }
}
