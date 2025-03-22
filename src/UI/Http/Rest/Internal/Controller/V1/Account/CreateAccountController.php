<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Internal\Controller\V1\Account;

use App\Application\Command\Account\Create\CreateAccountCommand;
use App\Domain\Core\User\Entity\User;
use App\UI\Http\Rest\Internal\Controller\CommandController;
use App\UI\Http\Rest\Internal\DTO\Account\CreateAccountRequest;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Bundle\SecurityBundle\Security;

#[OA\Tag(name: 'Account')]
final class CreateAccountController extends CommandController
{
    public string $dtoClass = CreateAccountRequest::class;

    #[OA\Post(
        summary: 'Create account',
        security: [['Bearer' => []]],
        responses: [
            new OA\Response(
                response: Response::HTTP_CREATED,
                description: 'Account created successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', type: 'string', example: 'ed22358b-2331-45cf-b370-d347d3cac532')
                    ]
                )
            ),
            new OA\Response(
                response: Response::HTTP_BAD_REQUEST,
                description: 'Bad request - Account already exists or validation error',
                content: new OA\JsonContent(
                    oneOf: [
                        new OA\Schema(
                            properties: [
                                new OA\Property(property: 'status', type: 'string', example: 'error'),
                                new OA\Property(
                                    property: 'messages',
                                    type: 'array',
                                    items: new OA\Items(type: 'string'),
                                    example: ['Account already exists.']
                                ),
                                new OA\Property(property: 'code', type: 'integer', example: 400)
                            ]
                        ),
                        new OA\Schema(
                            properties: [
                                new OA\Property(property: 'status', type: 'string', example: 'error'),
                                new OA\Property(
                                    property: 'messages',
                                    type: 'array',
                                    items: new OA\Items(type: 'string'),
                                    example: ['The accountName field is required.']
                                ),
                                new OA\Property(property: 'code', type: 'integer', example: 400)
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(response: Response::HTTP_INTERNAL_SERVER_ERROR, description: 'Internal server error')
        ]
    )]
    #[OA\Parameter(
        name: 'accountName',
        description: 'Account name',
        in: 'query',
        required: true,
        schema: new OA\Schema(type: 'string')
    )]
    public function __invoke(
        Request                 $request,
        MessageBusInterface     $messageBus,
        Security $security,
    ): JsonResponse
    {
        $accountName = $request->get('accountName');
        /* @var User $user */
        $user = $security->getUser();
        $command = new CreateAccountCommand(
            user: $user,
            accountName: $accountName,
        );
        $envelope = $messageBus->dispatch($command);
        $handledStamp = $envelope->last(HandledStamp::class);
        if (!$handledStamp) {
            throw new \RuntimeException('No handler was found for this query or handler failed to execute.');
        }
        $accountId = $handledStamp->getResult();
        return new JsonResponse(['id' => $accountId], Response::HTTP_CREATED);
    }
}
