<?php

declare(strict_types=1);

namespace App\Infrastructure\Core\Auth\Security;

use App\Domain\Core\Auth\Repository\TokenRepositoryInterface;
use App\Domain\Core\User\Repository\UserRepositoryInterface;
use App\Infrastructure\Core\Auth\Service\JwtEncoder;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

final class JwtAuthenticator extends AbstractAuthenticator implements AuthenticationEntryPointInterface
{
    public function __construct(
        private readonly JwtEncoder               $jwtEncoder,
        private readonly UserRepositoryInterface  $userRepository,
        private readonly TokenRepositoryInterface $tokenRepository,
        private readonly LoggerInterface          $logger
    )
    {
    }

    public function supports(Request $request): ?bool
    {
        $this->logger->info('Checking if request supports JWT authentication');
        return $request->headers->has('Authorization');
    }

    public function authenticate(Request $request): Passport
    {
        try {
            $authHeader = $request->headers->get('Authorization');
            if (!$authHeader) {
                throw new CustomUserMessageAuthenticationException('No Authorization header provided');
            }

            if (!preg_match('/Bearer\s+(.+)/', $authHeader, $matches)) {
                throw new CustomUserMessageAuthenticationException('Invalid Authorization header format');
            }

            $token = $matches[1];
            $this->logger->info('Attempting to authenticate with token', ['token' => substr($token, 0, 10) . '...']);

            // Перевіряємо, чи токен не в чорному списку
            if ($this->tokenRepository->isBlacklisted($token)) {
                throw new CustomUserMessageAuthenticationException('Token is blacklisted');
            }

            // Декодуємо JWT токен
            $payload = $this->jwtEncoder->decode($token);

            if (!isset($payload['sub'])) {
                throw new CustomUserMessageAuthenticationException('Invalid token payload');
            }

            $userId = $payload['sub'];
            $this->logger->info('Token decoded successfully', ['user_id' => $userId]);

            return new SelfValidatingPassport(
                new UserBadge($userId, function (string $userId) {
                    $user = $this->userRepository->findOneBy(['uuid' => Uuid::fromString($userId)->getBytes()]);

                    if (!$user) {
                        throw new CustomUserMessageAuthenticationException('User not found');
                    }
                    return $user;
                })
            );
        } catch (\Exception $e) {
            $this->logger->error('Authentication failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw new CustomUserMessageAuthenticationException($e->getMessage());
        }
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $this->logger->info('Authentication successful', [
            'user' => $token->getUserIdentifier(),
            'firewall' => $firewallName
        ]);
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $this->logger->error('Authentication failed', ['error' => $exception->getMessage()]);
        return new JsonResponse([
            'message' => $exception->getMessage()
        ], Response::HTTP_UNAUTHORIZED);
    }

    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        $this->logger->warning('Authentication required', [
            'uri' => $request->getUri()
        ]);

        return new JsonResponse([
            'message' => 'Authentication required'
        ], Response::HTTP_UNAUTHORIZED);
    }
} 