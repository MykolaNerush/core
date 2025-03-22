<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Internal\Controller\V1\Auth;

use App\Application\Command\User\Auth\SignIn\SignInCommand;
use App\Domain\Core\Auth\Event\AuthenticationFailureEvent;
use App\Domain\Core\Auth\Event\AuthenticationSuccessEvent;
use App\UI\Http\Rest\Internal\Controller\CommandController;
use App\UI\Http\Rest\Internal\DTO\Auth\SignInUserRequest;
use App\UI\Http\Rest\Shared\Response\BaseJsonApiFormatterInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

#[OA\Tag(name: 'Auth')]
final class SignInController extends CommandController
{
    public string $dtoClass = SignInUserRequest::class;

    public function __construct(
        protected BaseJsonApiFormatterInterface   $formatter,
        protected UrlGeneratorInterface           $router,
        private readonly EventDispatcherInterface $eventDispatcher,
    )
    {
        parent::__construct($formatter, $router);
    }

    #[OA\Post(
        description: 'Use this endpoint to get JWT token for authentication. After getting the token, click \'Authorize\' button in Swagger UI and enter the token in format: Bearer your_token',
        summary: 'Sign in to get JWT token',
        requestBody: new OA\RequestBody(
            description: 'Credentials',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'email', description: 'User email', type: 'string', format: 'email'),
                    new OA\Property(property: 'password', description: 'User password', type: 'string', format: 'password')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: Response::HTTP_OK,
                description: 'Success',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'token', description: 'JWT token to be used in Authorization header with Bearer prefix', type: 'string'),
                        new OA\Property(property: 'refresh_token', description: 'Refresh token for getting new access token', type: 'string'),
                        new OA\Property(property: 'expires_in', description: 'Token expiration time in seconds', type: 'integer')
                    ]
                )
            ),
            new OA\Response(response: Response::HTTP_BAD_REQUEST, description: 'Bad Request'),
            new OA\Response(response: Response::HTTP_UNAUTHORIZED, description: 'Invalid credentials'),
            new OA\Response(response: Response::HTTP_INTERNAL_SERVER_ERROR, description: 'Internal server error')
        ]
    )]
    public function __invoke(Request $request, MessageBusInterface $messageBus): JsonResponse
    {
        $ip = $request->getClientIp() ?? 'unknown';
        $email = $request->get('email');
        $password = $request->get('password');
        $command = new SignInCommand($email, $password);

        try {
            $envelope = $messageBus->dispatch($command);
            $handledStamp = $envelope->last(HandledStamp::class);

            if (!$handledStamp) {
                throw new \RuntimeException('No handler was found for this query or handler failed to execute.');
            }

            $result = $handledStamp->getResult();
            $uuid = $result['uuid'];
            unset($result['uuid']);

            $this->eventDispatcher->dispatch(
                new AuthenticationSuccessEvent($uuid, $ip)
            );

            return new JsonResponse($result);
        } catch (\Exception $e) {
            $this->eventDispatcher->dispatch(
                new AuthenticationFailureEvent($email, $ip, $e->getMessage())
            );

            throw $e;
        }
    }
}
