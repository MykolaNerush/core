<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Internal\Controller\V1\User;

use App\Application\Command\User\ConfirmEmail\ConfirmEmailCommand;
use App\UI\Http\Rest\Internal\DTO\User\ConfirmEmailRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class ConfirmEmailController
{
    public string $dtoClass = ConfirmEmailRequest::class;

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