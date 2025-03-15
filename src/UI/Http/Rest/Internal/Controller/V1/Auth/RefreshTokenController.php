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

final class RefreshTokenController extends CommandController
{
    public string $dtoClass = RefreshTokenRequest::class;

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
