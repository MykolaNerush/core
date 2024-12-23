<?php

declare(strict_types=1);

namespace App\Domain\Core\Account\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;
use App\Domain\Core\User\Entity\User;
use App\Domain\Core\Account\Enum\Status;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity(repositoryClass: 'App\Infrastructure\Core\Account\Repository\AccountRepository')]
#[ORM\Table(name: 'accounts')]
class Account
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid_binary', length: 16)]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    private UuidInterface $uuid;

    #[ORM\Column(type: 'string', length: 255)]
    private string $accountName;

    #[ORM\Column(type: 'float')]
    private float $balance;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?DateTimeImmutable $updatedAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?DateTimeImmutable $deletedAt;

    #[ORM\Column(type: 'string', enumType: Status::class)]
    private Status $status;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'accounts', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'user_uuid', referencedColumnName: 'uuid', onDelete: 'CASCADE')]
    private User $user;

    public function __construct(string $accountName, float $balance, User $user, Status $status = Status::ACTIVE)
    {
        $this->uuid = Uuid::uuid4();
        $this->accountName = $accountName;
        $this->balance = $balance;
        $this->status = $status;
        $this->user = $user;
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = null;
        $this->deletedAt = null;
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

    public function getDeletedAt(): ?DateTimeImmutable
    {
        return $this->deletedAt;
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
