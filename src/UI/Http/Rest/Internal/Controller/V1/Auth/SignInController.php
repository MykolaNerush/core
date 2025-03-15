<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Internal\Controller\V1\Auth;

use App\Application\Command\User\Auth\SignIn\SignInCommand;
use App\UI\Http\Rest\Internal\Controller\CommandController;
use App\UI\Http\Rest\Internal\DTO\Auth\SignInUserRequest;
use App\UI\Http\Rest\Shared\Response\BaseJsonApiFormatterInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class SignInController extends CommandController
{
    public function __construct(
        protected BaseJsonApiFormatterInterface $formatter,
        protected UrlGeneratorInterface         $router,
    )
    {
        parent::__construct($formatter, $router);
    }

    public string $dtoClass = SignInUserRequest::class;

    public function __invoke(Request $request, MessageBusInterface $messageBus): JsonResponse
    {
        $email = $request->get('email');
        $password = $request->get('password');
        $command = new SignInCommand($email, $password);
        $envelope = $messageBus->dispatch($command);
        $handledStamp = $envelope->last(HandledStamp::class);
        if (!$handledStamp) {
            throw new \RuntimeException('No handler was found for this query or handler failed to execute.');
        }
        $result = $handledStamp->getResult();
        return new JsonResponse($result);
    }
}
