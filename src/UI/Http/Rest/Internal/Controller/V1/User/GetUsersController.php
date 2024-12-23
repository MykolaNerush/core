<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Internal\Controller\V1\User;

use App\Application\Query\User\GetUsers\GetUsersQuery;
use App\UI\Http\Rest\Internal\Controller\QueryController;
use App\UI\Http\Rest\Internal\DTO\Users\GetUsersRequest;
use App\UI\Http\Rest\Internal\Response\JsonApiFormatter;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


#[OA\Tag(name: 'User')]
final class GetUsersController extends QueryController
{
    public function __construct(
        JsonApiFormatter                     $formatter,
        UrlGeneratorInterface                $router,
        private readonly MessageBusInterface $messageBus,
    )
    {
        parent::__construct($formatter, $router, GetUsersRequest::class);
    }

    #[OA\Get(
        summary: 'Get users',
        responses: [
            new OA\Response(response: 200, description: 'Ok'),
            new OA\Response(response: 400, description: 'Bad request'),
            new OA\Response(response: 401, description: 'Unauthorized'),
            new OA\Response(response: 404, description: 'Empty collection'),
            new OA\Response(response: 500, description: 'Internal server error')
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
        $query = new GetUsersQuery(
            routeGenerator: $this->routeWithPageAsCallable($request),
            page: (int)$request->get('page', 1),
            perPage: (int)$request->get('perPage', 10),
            order: $request->get('order', 'ASC'),
            sort: $request->get('sort', 'createdAt'),
            uuid: $request->get('filter')['uuid'] ?? null,
            emailSearch: $request->get('filter')['email'] ?? null,
        );
        $envelope = $this->messageBus->dispatch($query);
        $handledStamp = $envelope->last(HandledStamp::class);
        if (!$handledStamp) {
            throw new \RuntimeException('No handler was found for this query or handler failed to execute.');
        }

        $users = $handledStamp->getResult();
        return $this->jsonCollection($users);

    }
}
