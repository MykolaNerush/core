<?php

declare(strict_types=1);

namespace App\Application\Command\User\Auth\SignIn;

use App\Domain\Core\User\Repository\UserRepositoryInterface;
use App\Domain\Core\User\Service\Auth\TokenService;
use App\Infrastructure\Shared\Exception\InvalidCredentialsException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsMessageHandler]
final readonly class SignInCommandHandler
{
    public function __construct(
        private UserRepositoryInterface     $userRepository,
        private TokenService                $tokenService,
        private UserPasswordHasherInterface $passwordHasher
    )
    {
    }

    public function __invoke(SignInCommand $command): array
    {
        $user = $this->userRepository->findOneBy(['email' => $command->email]);
        if (!$user || !$this->passwordHasher->isPasswordValid($user, $command->password)) {
            throw new InvalidCredentialsException();
        }
        return [
            'token' => $this->tokenService->createToken($user)
        ];

    }
}