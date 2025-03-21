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

/**
 * @OA\Post(
 *   path="/api/v1/internal/auth/sign-in",
 *   summary="Sign in to get JWT token",
 *   description="Use this endpoint to get JWT token for authentication. After getting the token, click 'Authorize' button in Swagger UI and enter the token in format: Bearer your_token",
 *   tags={"Auth"},
 *   @OA\RequestBody(
 *     description="Credentials",
 *     @OA\JsonContent(
 *       @OA\Property(property="email", type="string", format="email", description="User email"),
 *       @OA\Property(property="password", type="string", format="password", description="User password")
 *     )
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Success",
 *     @OA\JsonContent(
 *       @OA\Property(property="token", type="string", description="JWT token to be used in Authorization header with Bearer prefix")
 *     )
 *   ),
 *   @OA\Response(response=400, description="Bad Request"),
 *   @OA\Response(response=401, description="Invalid credentials"),
 *   @OA\Response(response=500, description="Internal server Error")
 * )
 */
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
