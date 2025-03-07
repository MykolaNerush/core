<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Services\User;

use App\Application\Shared\Services\User\UserConfirmationInterface;
use App\Domain\Core\User\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;

readonly class UserConfirmationService implements UserConfirmationInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    )
    {
    }

    public function generateConfirmationToken(User $user): string
    {
        $plainToken = Uuid::uuid4()->__toString();

        $user->setConfirmationToken($plainToken);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $plainToken;
    }

    public function verifyToken(User $user, string $token): bool
    {
        $storedToken = $user->getConfirmationToken();

        if (!$storedToken) {
            return false;
        }

        return hash_equals($storedToken, $token);
    }
}