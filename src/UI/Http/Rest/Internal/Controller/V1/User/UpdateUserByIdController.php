<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Internal\Controller\V1\User;

use App\Application\Command\User\Update\UpdateUserCommand;
use App\Domain\Core\Account\Entity\Account;
use App\Domain\Shared\Security\ResourceVoter;
use App\UI\Http\Rest\Internal\Controller\CommandController;
use App\UI\Http\Rest\Internal\DTO\User\UpdateUserRequest;
use OpenApi\Attributes as OA;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

#[OA\Tag(name: 'User')]
final class UpdateUserByIdController extends CommandController
{
    public string $dtoClass = UpdateUserRequest::class;

    #[OA\Post(
        summary: "Update user by ID",
        security: [['Bearer' => []]],
        requestBody: new OA\RequestBody(
            description: "User",
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "email", type: "string"),
                    new OA\Property(property: "password", type: "string")
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
            new OA\Response(response: Response::HTTP_OK, description: "User updated successfully"),
            new OA\Response(response: Response::HTTP_BAD_REQUEST, description: "Bad Request"),
            new OA\Response(response: Response::HTTP_UNAUTHORIZED, description: "Unauthorized"),
            new OA\Response(response: Response::HTTP_CONFLICT, description: "Conflict"),
            new OA\Response(response: Response::HTTP_INTERNAL_SERVER_ERROR, description: "Internal server Error")
        ]
    )]
    public function __invoke(string $uuid, Request $request, MessageBusInterface $messageBus): JsonResponse
    {
        $this->denyAccessUnlessGranted(ResourceVoter::UPDATE, ['repo' => Account::class, 'uuid' => $uuid]);
        $command = new UpdateUserCommand(
            currentUuid: Uuid::fromString($uuid),
            name: $request->get('name'),
            email: $request->get('email'),
            password: $request->get('password'),
        );
        $envelope = $messageBus->dispatch($command);
        $handledStamp = $envelope->last(HandledStamp::class);
        if (!$handledStamp) {
            throw new \RuntimeException('No handler was found for this query or handler failed to execute.');
        }
        return new JsonResponse(null, Response::HTTP_OK);
    }
}
