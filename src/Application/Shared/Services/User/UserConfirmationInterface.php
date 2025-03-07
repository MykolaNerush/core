<?php

declare(strict_types=1);

namespace App\Application\Shared\Services\User;

use App\Domain\Core\User\Entity\User;

interface UserConfirmationInterface
{
    public function generateConfirmationToken(User $user): string;

    public function verifyToken(User $user, string $token): bool;
}
