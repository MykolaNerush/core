<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Internal\Controller\V1\User;

use App\Application\Command\User\ConfirmEmail\ConfirmEmailCommand;
use App\UI\Http\Rest\Internal\DTO\User\ConfirmEmailRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'User')]
class ConfirmEmailController
{
    public string $dtoClass = ConfirmEmailRequest::class;

    #[OA\Get(
        description: 'Use this endpoint to confirm user email using the confirmation link sent to their email address',
        summary: 'Confirm user email',
        requestBody: new OA\RequestBody(
            description: 'Confirmation data',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'email', description: 'User email address', type: 'string', format: 'email'),
                    new OA\Property(property: 'token', description: 'Confirmation token received in email', type: 'string'),
                    new OA\Property(property: 'signature', description: 'Security signature to verify the request', type: 'string')
                ]
            )
        ),
        responses: [
            new OA\Response(response: Response::HTTP_OK, description: 'Email confirmed successfully'),
            new OA\Response(response: Response::HTTP_BAD_REQUEST, description: 'Invalid confirmation link or data'),
            new OA\Response(response: Response::HTTP_INTERNAL_SERVER_ERROR, description: 'Internal server error')
        ]
    )]
    public function __invoke(Request $request, MessageBusInterface $messageBus): JsonResponse
    {
        $email = $request->get('email');
        $command = new ConfirmEmailCommand(email: $email);
        $envelope = $messageBus->dispatch($command);
        $handledStamp = $envelope->last(HandledStamp::class);
        if (!$handledStamp) {
            throw new \RuntimeException('No handler was found for this query or handler failed to execute.');
        }
        return new JsonResponse('Email confirmed successfully!');
    }
}