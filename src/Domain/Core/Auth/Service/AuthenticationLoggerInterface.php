<?php

declare(strict_types=1);

namespace App\Domain\Core\Auth\Service;

interface AuthenticationLoggerInterface
{
    public function logSuccess(string $userId, string $ip): void;
    public function logFailure(string $email, string $ip, string $reason): void;
    public function getFailureCount(string $ip, \DateTimeImmutable $since): int;
} 