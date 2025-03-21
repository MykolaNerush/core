<?php

declare(strict_types=1);

namespace App\Domain\Core\Auth\Token;

final readonly class Token implements TokenInterface
{
    private \DateTimeImmutable $expiresAtDateTime;

    public function __construct(
        private string $token,
        private array $payload,
        private string $userId,
        private TokenType $type,
        private int $expiresAt
    ) {
        $this->expiresAtDateTime = \DateTimeImmutable::createFromFormat('U', (string)$expiresAt);
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getType(): TokenType
    {
        return $this->type;
    }

    public function getExpiresAt(): \DateTimeImmutable
    {
        return $this->expiresAtDateTime;
    }

    public function isExpired(): bool
    {
        return time() > $this->expiresAt;
    }
} 