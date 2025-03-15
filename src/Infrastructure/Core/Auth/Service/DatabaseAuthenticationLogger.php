<?php

declare(strict_types=1);

namespace App\Infrastructure\Core\Auth\Service;

use App\Domain\Core\Auth\Service\AuthenticationLoggerInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

readonly class DatabaseAuthenticationLogger implements AuthenticationLoggerInterface
{
    public function __construct(
        private Connection $connection
    )
    {
    }

    /**
     * @throws Exception
     */
    public function logSuccess(string $userId, string $ip): void
    {
//        $this->connection->insert('authentication_log', [
//            'user_id' => $userId,
//            'ip' => $ip,
//            'status' => 'success',
//            'created_at' => (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
//        ]);
    }

    /**
     * @throws Exception
     */
    public function logFailure(string $email, string $ip, string $reason): void
    {
//        $this->connection->insert('authentication_log', [
//            'email' => $email,
//            'ip' => $ip,
//            'status' => 'failure',
//            'reason' => $reason,
//            'created_at' => (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
//        ]);
    }

    /**
     * @throws Exception
     */
    public function getFailureCount(string $ip, \DateTimeImmutable $since): int
    {
        return (int)$this->connection->fetchOne(
            'SELECT COUNT(*) FROM authentication_log WHERE ip = ? AND status = ? AND created_at >= ?',
            [$ip, 'failure', $since->format('Y-m-d H:i:s')]
        );
    }
} 