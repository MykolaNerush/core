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

final class RevokeTokenController extends CommandController
{
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
