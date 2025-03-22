<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Internal\Controller\V1\Auth;

use App\Application\Command\User\Auth\RevokeToken\RevokeTokenCommand;
use App\UI\Http\Rest\Internal\Controller\CommandController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Auth')]
final class RevokeTokenController extends CommandController
{
    #[OA\Post(
        summary: 'Revoke JWT token',
        security: [['Bearer' => []]],
        responses: [
            new OA\Response(response: Response::HTTP_NO_CONTENT, description: 'Token revoked successfully'),
            new OA\Response(response: Response::HTTP_BAD_REQUEST, description: 'Bad request - No token provided'),
            new OA\Response(response: Response::HTTP_INTERNAL_SERVER_ERROR, description: 'Internal server error')
        ]
    )]
    public function __invoke(Request $request, MessageBusInterface $messageBus): JsonResponse
    {
        $token = $request->headers->get('Authorization');
        if (!$token) {
            return new JsonResponse(['error' => 'No token provided'], Response::HTTP_BAD_REQUEST);
        }

        $token = str_replace('Bearer ', '', $token);
        $command = new RevokeTokenCommand($token);
        $envelope = $messageBus->dispatch($command);
        $handledStamp = $envelope->last(HandledStamp::class);
        if (!$handledStamp) {
            throw new \RuntimeException('No handler was found for this query or handler failed to execute.');
        }
        return new JsonResponse(status: 204);
    }
}
