<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Internal\Controller\V1\Auth;

use App\Application\Command\User\Auth\RefreshToken\RefreshTokenCommand;
use App\UI\Http\Rest\Internal\Controller\CommandController;
use App\UI\Http\Rest\Internal\DTO\Auth\RefreshTokenRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

#[OA\Tag(name: 'Auth')]
final class RefreshTokenController extends CommandController
{
    public string $dtoClass = RefreshTokenRequest::class;

    #[OA\Post(
        summary: 'Refresh JWT token',
        security: [['Bearer' => []]],
        requestBody: new OA\RequestBody(
            description: 'Refresh token',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'refreshToken', description: 'Refresh token received from sign-in or previous refresh', type: 'string')
                ]
            )
        ),
        responses: [
            new OA\Response(response: Response::HTTP_OK, description: 'Tokens refreshed successfully'),
            new OA\Response(response: Response::HTTP_BAD_REQUEST, description: 'Bad request - Invalid refresh token or validation error'),
            new OA\Response(response: Response::HTTP_INTERNAL_SERVER_ERROR, description: 'Internal server error')
        ]
    )]
    public function __invoke(Request $request, MessageBusInterface $messageBus): JsonResponse
    {
        $refreshToken = $request->get('refreshToken');
        $command = new RefreshTokenCommand($refreshToken);
        $envelope = $messageBus->dispatch($command);
        $handledStamp = $envelope->last(HandledStamp::class);
        if (!$handledStamp) {
            throw new \RuntimeException('No handler was found for this query or handler failed to execute.');
        }
        $result = $handledStamp->getResult();
        return new JsonResponse($result);
    }
}
