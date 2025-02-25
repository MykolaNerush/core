<?php

declare(strict_types=1);

namespace App\Domain\Core\Account\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;
use App\Domain\Core\User\Entity\User;
use App\Domain\Core\Account\Enum\Status;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity]
#[ORM\Table(name: 'accounts')]
class Account
{
    #[ORM\Id, ORM\GeneratedValue(strategy: 'NONE'), ORM\Column(type: 'uuid_binary', length: 16)]
    private UuidInterface $uuid;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $accountName;

    #[ORM\Column(type: Types::INTEGER)]
    private float $balance;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?DateTimeImmutable $updatedAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?DateTimeImmutable $deletedAt = null;

    #[ORM\Column(type: Types::STRING, enumType: Status::class)]
    private Status $status;

    #[
        ORM\ManyToOne(targetEntity: User::class, cascade: ['persist'], inversedBy: 'accounts'),
        ORM\JoinColumn(name: 'user_uuid', referencedColumnName: 'uuid', onDelete: 'CASCADE')
    ]
    private User $user;

    public function __construct(string $accountName, float $balance, User $user, Status $status = Status::ACTIVE)
    {
        $this->uuid = Uuid::uuid4();
        $this->accountName = $accountName;
        $this->balance = $balance;
        $this->status = $status;
        $this->user = $user;
        $this->createdAt = new DateTimeImmutable();
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getAccountName(): string
    {
        return $this->accountName;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }

    public function getDeletedAt(): ?DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(): void
    {
        $this->deletedAt = new DateTimeImmutable();
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }
}
