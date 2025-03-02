<?php

declare(strict_types=1);

namespace App\Domain\Core\Account\Entity;

use App\Domain\Shared\Entity\TimestampableEntity;
use Broadway\ReadModel\SerializableReadModel;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;
use App\Domain\Core\User\Entity\User;
use App\Domain\Core\Account\Enum\Status;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity]
#[ORM\Table(name: 'accounts')]
class Account extends TimestampableEntity implements SerializableReadModel
{
    #[ORM\Id, ORM\GeneratedValue(strategy: 'NONE'), ORM\Column(type: 'uuid_binary', length: 16)]
    private UuidInterface $uuid;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $accountName;

    #[ORM\Column(type: Types::INTEGER)]
    private int $balance = 0;

    #[ORM\Column(type: Types::STRING, enumType: Status::class)]
    private Status $status;

    #[
        ORM\ManyToOne(targetEntity: User::class, cascade: ['persist'], inversedBy: 'accounts'),
        ORM\JoinColumn(name: 'user_uuid', referencedColumnName: 'uuid', onDelete: 'CASCADE')
    ]
    private User $user;

    public function __construct(string $accountName, User $user, Status $status = Status::ACTIVE)
    {
        $this->uuid = Uuid::uuid4();
        $this->accountName = $accountName;
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

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setAccountName(string $accountName): void
    {
        $this->accountName = $accountName;
    }

    public function setBalance(int $balance): void
    {
        $this->balance = $balance;
    }

    public function setStatus(Status $status): void
    {
        $this->status = $status;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getId(): string
    {
        return $this->uuid->toString();
//        return Uuid::fromBytes($this->uuid->getBytes())->toString();
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function deserialize(array $data): self
    {
        return new self(
//            Uuid::fromString($data['uuid'] ?? Uuid::uuid4()->toString())
            is_string($data['accountName']) ? $data['name'] : '',
            !empty($data['status']) ? $data['status'] : '',
            !empty($data['user']) ? $data['user'] : '',
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function serialize(): array
    {
        return [
            'id' => $this->getUuid(),
            'uuid' => $this->getUuid(),
            'name' => $this->getAccountName(),
            'status' => $this->getStatus()->label(),
            'user' => $this->getUser(),
            'timestamps' => [
                'createdAt' => $this->getCreatedAt()?->format('Y-m-d H:i:s'),
                'updatedAt' => $this->getUpdatedAt()?->format('Y-m-d H:i:s'),
            ],
        ];
    }

    public function update(?string $accountName = null): void
    {
        if ($accountName) {
            $this->setAccountName($accountName);
        }
        $this->setUpdatedAt();
    }
}
