<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Internal\Controller\V1\Account;

use App\Application\Command\Account\Update\UpdateAccountCommand;
use App\Domain\Core\Account\Repository\AccountRepositoryInterface;
use App\Domain\Shared\Security\ResourceVoter;
use App\UI\Http\Rest\Internal\DTO\Account\UpdateAccountRequest;
use OpenApi\Attributes as OA;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

#[OA\Tag(name: 'Account')]
final class UpdateAccountByIdController extends AbstractController
{
    public string $dtoClass = UpdateAccountRequest::class;

    #[OA\Post(
        summary: "Update account by ID",
        security: [['Bearer' => []]],
        requestBody: new OA\RequestBody(
            description: "Account",
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "accountName", type: "string"),
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
            new OA\Response(response: Response::HTTP_OK, description: "Account updated successfully"),
            new OA\Response(response: Response::HTTP_BAD_REQUEST, description: "Bad Request"),
            new OA\Response(response: Response::HTTP_UNAUTHORIZED, description: "Unauthorized"),
            new OA\Response(response: Response::HTTP_CONFLICT, description: "Conflict"),
            new OA\Response(response: Response::HTTP_INTERNAL_SERVER_ERROR, description: "Internal server Error")
        ]
    )]
    public function __invoke(string $uuid, Request $request, MessageBusInterface $messageBus, AccountRepositoryInterface $accountRepository,): JsonResponse
    {
        $account = $accountRepository->findOneBy(['uuid' => Uuid::fromString($uuid)->getBytes()]);

        $this->denyAccessUnlessGranted(ResourceVoter::UPDATE, $account);

        $command = new UpdateAccountCommand(
            currentUuid: Uuid::fromString($uuid),
            accountName: $request->get('accountName'),
        );
        $envelope = $messageBus->dispatch($command);
        $handledStamp = $envelope->last(HandledStamp::class);
        if (!$handledStamp) {
            throw new \RuntimeException('No handler was found for this query or handler failed to execute.');
        }
        return new JsonResponse(null, Response::HTTP_OK);
    }
}
