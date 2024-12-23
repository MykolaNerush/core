<?php

declare(strict_types=1);

namespace App\Domain\Core\User\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use App\Domain\Core\User\Enum\Status;
use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use App\Domain\Core\Account\Entity\Account;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
class User
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
}
