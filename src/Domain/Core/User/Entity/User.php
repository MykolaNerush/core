<?php

declare(strict_types=1);

namespace App\Domain\Core\User\Entity;

use App\Domain\Shared\Entity\TimestampableEntity;
use Broadway\ReadModel\SerializableReadModel;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use App\Domain\Core\User\Enum\Status;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use App\Domain\Core\Account\Entity\Account;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
class User extends TimestampableEntity implements SerializableReadModel
{
    #[ORM\Id, ORM\GeneratedValue(strategy: 'NONE'), ORM\Column(type: 'uuid_binary', length: 16)]
    private UuidInterface $uuid;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $name;

    #[ORM\Column(type: Types::STRING, unique: true)]
    private string $email;

    #[ORM\Column(type: Types::STRING)]
    private string $password;

    #[ORM\Column(type: Types::STRING, enumType: Status::class)]
    private Status $status;

    /**
     * @var Collection<int, Account>
     */
    #[ORM\OneToMany(targetEntity: Account::class, mappedBy: 'user')]
    private Collection $accounts;

    public function __construct(
        string $name,
        string $email,
        string $password,
        Status $status = Status::NEW,
    )
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

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function setStatus(Status $status): void
    {
        $this->status = $status;
    }

    /**
     * @return Collection<int, Account>|null
     */
    public function getAccounts(): ?Collection
    {
        return $this->accounts;
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function deserialize(array $data): self
    {
        return new self(
//            Uuid::fromString($data['uuid'] ?? Uuid::uuid4()->toString())
            is_string($data['name']) ? $data['name'] : '',
            is_string($data['email']) ? $data['email'] : '',
            is_string($data['password']) ? $data['password'] : '',
        );
    }


    /**
     * @return array<string, mixed>
     */
    public function serialize(): array
    {
        $accounts = [];

        if ($this->getAccounts()) {
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
        }

        return [
            'id' => $this->getUuid(),
            'uuid' => $this->getUuid(),
            'name' => $this->getName(),
            'email' => $this->getEmail(),
            'status' => $this->getStatus()->label(),
            'account' => $accounts,
            'timestamps' => [
                'createdAt' => $this->getCreatedAt()->format('Y-m-d H:i:s'),
                'updatedAt' => $this->getUpdatedAt()?->format('Y-m-d H:i:s'),
            ],
        ];
    }


    public function getId(): string
    {
        return $this->uuid->toString();
//        return Uuid::fromBytes($this->uuid->getBytes())->toString();
    }

    public function update(
        ?string $name = null,
        ?string $email = null,
        ?string $password = null,
        ?Status $status = null,
    ): void
    {
        if ($name) {
            $this->setName($name);
        }
        if ($email) {
            $this->setEmail($email);
        }
        if ($password) {
            $this->setPassword($password);
        }
        if ($status) {
            $this->status = $status;
        }
        $this->setUpdatedAt();
    }
}
