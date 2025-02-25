<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Internal\Controller\V1\Account;

use App\Application\Command\Account\Create\CreateAccountCommand;
use App\UI\Http\Rest\Internal\Controller\CommandController;
use App\UI\Http\Rest\Internal\DTO\Accounts\CreateAccountRequest;
use OpenApi\Attributes as OA;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

#[OA\Tag(name: 'Account')]
final class CreateAccountController extends CommandController
{
    public string $dtoClass = CreateAccountRequest::class;

    #[OA\Post(
        summary: 'Create account',
        security: [['Bearer' => []]],
        responses: [
            new OA\Response(response: Response::HTTP_CREATED, description: 'Account created successfully'),
            new OA\Response(response: Response::HTTP_BAD_REQUEST, description: 'Bad request'),
            new OA\Response(response: Response::HTTP_CONFLICT, description: 'Conflict'),
            new OA\Response(response: Response::HTTP_INTERNAL_SERVER_ERROR, description: 'Internal server error')
        ]
    )]
    #[OA\Parameter(
        name: 'name',
        description: 'Account name',
        in: 'query',
        required: true,
        schema: new OA\Schema(type: 'string')
    )]
    public function __invoke(Request $request, MessageBusInterface $messageBus): JsonResponse
    {
        $name = $request->get('name');
        $uuid = Uuid::uuid4();
        $command = new CreateAccountCommand(
            uuid: $uuid,
            name: $name,
        );
        $envelope = $messageBus->dispatch($command);
        $handledStamp = $envelope->last(HandledStamp::class);
        if (!$handledStamp) {
            throw new \RuntimeException('No handler was found for this query or handler failed to execute.');
        }
        return new JsonResponse(['id' => $uuid], Response::HTTP_CREATED);

    }
}
