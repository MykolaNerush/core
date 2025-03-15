<?php

declare(strict_types=1);

namespace App\Domain\Core\Auth\Repository;

use App\Domain\Core\Auth\Token\TokenInterface;

interface TokenRepositoryInterface
{
    public function save(TokenInterface $token): void;
    public function findByToken(string $token): ?TokenInterface;
    public function isBlacklisted(string $token): bool;
    public function blacklist(string $token): void;
}