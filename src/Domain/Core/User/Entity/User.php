<?php

declare(strict_types=1);

namespace App\Domain\Core\User\Entity;

use Assert\AssertionFailedException;
use Broadway\ReadModel\SerializableReadModel;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use App\Domain\Core\User\Enum\Status;
use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use App\Domain\Core\Account\Entity\Account;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
class User implements SerializableReadModel
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid_binary', length: 16)]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    private UuidInterface $uuid;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'string', unique: true)]
    private string $email;

    #[ORM\Column(type: 'string')]
    private string $password;

    #[ORM\Column(type: 'string', enumType: Status::class)]
    private Status $status;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?DateTimeImmutable $updatedAt = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?DateTimeImmutable $deletedAt = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Account::class)]
    private $accounts;

    public function __construct(string $name, string $email, string $password, Status $status)
    {
        $this->uuid = Uuid::uuid4();
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->status = $status;
        $this->createdAt = new DateTimeImmutable();
        $this->accounts = new ArrayCollection();
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
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

    /**
     * @return Account[]
     */
    public function getAccounts(): \Doctrine\Common\Collections\Collection
    {
        return $this->accounts;
    }

    /**
     * @param array<string, string|mixed> $data
     *
     * @throws AssertionFailedException
     */
    public static function deserialize(array $data): self
    {
        $instance = new self();

        $instance->uuid = Uuid::fromString($data['uuid']);
//        $instance->account = \App\Domain\Core\Account\Account::deserialize($data['account']);
        return $instance;
    }
    /**
     * @return array<string, string|mixed>
     */
    public function serialize(): array
    {
        $accounts = [];

        foreach ($this->getAccounts() as $account) {
            $accounts[] = [
                'uuid' => $account->getUuid(),
                'accountName' => $account->getAccountName(),
                'balance' => $account->getBalance(),
                'createdAt' => $account->getCreatedAt(),
                'updatedAt' => $account->getUpdatedAt(),
                'deletedAt' => $account->getDeletedAt(),
                'status' => $account->getStatus(),
            ];
        }

        return [
            'id' => $this->getUuid(),
            'uuid' => $this->getUuid(),
            'name' => $this->getName(),
            'email' => $this->getEmail(),
            'status' => $this->getStatus()->label(),
            'account' => $accounts,
            'timestamps' => [
                'createdAt' => $this->getCreatedAt()?->format('Y-m-d H:i:s'),
                'updatedAt' => $this->getUpdatedAt()?->format('Y-m-d H:i:s'),
            ],
        ];
    }

    public function getId(): string
    {
        return Uuid::fromBytes($this->uuid->getBytes())->toString();
    }
}
