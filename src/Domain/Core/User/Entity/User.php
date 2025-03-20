<?php

declare(strict_types=1);

namespace App\Domain\Core\User\Entity;

use App\Domain\Core\UserVideo\Entity\UserVideo;
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
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Webmozart\Assert\Assert;

#[ORM\Entity, ORM\Table(name: 'users')]
class User extends TimestampableEntity implements UserInterface, PasswordAuthenticatedUserInterface, SerializableReadModel
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

    #[ORM\OneToOne(targetEntity: Account::class, mappedBy: 'user')]
    private ?Account $account = null;

    /**
     * @var Collection<int, UserRole>
     */
    #[ORM\ManyToMany(targetEntity: UserRole::class, inversedBy: 'users')]
    #[ORM\JoinTable(name: 'user_roles_mapping')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'uuid', onDelete: 'CASCADE')]
    #[ORM\InverseJoinColumn(name: 'role', referencedColumnName: 'role', onDelete: 'CASCADE')]
    #[ORM\OneToMany(targetEntity: UserRoleMapping::class, mappedBy: 'user')]
    private Collection $roles;

    /**
     * @var Collection<int, UserVideo>
     */
    #[ORM\OneToMany(targetEntity: UserVideo::class, mappedBy: 'user')]
    private Collection $userVideos;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $confirmationToken = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $isEmailConfirmed = false;
    private ?string $plainPassword;

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
        $this->roles = new ArrayCollection();
    }

    public function getConfirmationToken(): ?string
    {
        return $this->confirmationToken;
    }

    public function setConfirmationToken(?string $confirmationToken): self
    {
        $this->confirmationToken = $confirmationToken;
        return $this;
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

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): void
    {
        $this->account = $account;
    }

    /**
     * @return Collection<int, UserVideo>
     */
    public function getUserVideos(): Collection
    {
        return $this->userVideos;
    }

    public function addUserVideos(UserVideo $userVideos): void
    {
        if (!$this->userVideos->contains($userVideos)) {
            $this->userVideos->add($userVideos);
        }
    }

    public function removeUserVideos(UserVideo $userVideos): void
    {
        $this->userVideos->removeElement($userVideos);
    }

    /**
     * @param UserVideo[] $userVideos
     */
    public function setUserVideos(array $userVideos): void
    {
        $this->userVideos->clear();
        foreach ($userVideos as $video) {
            $this->userVideos->add($video);
        }
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function deserialize(array $data): self
    {
        return new self(
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
        $account = $this->account;
        $account = [
            'uuid' => $account?->getUuid(),
            'accountName' => $account?->getAccountName(),
            'balance' => $account?->getBalance(),
            'createdAt' => $account?->getCreatedAt(),
            'updatedAt' => $account?->getUpdatedAt(),
            'deletedAt' => $account?->getDeletedAt(),
            'status' => $account?->getStatus(),
        ];

        return [
            'id' => $this->getUuid(),
            'uuid' => $this->getUuid(),
            'name' => $this->getName(),
            'email' => $this->getEmail(),
            'status' => $this->getStatus()->label(),
            'account' => $account,
            'timestamps' => [
                'createdAt' => $this->getCreatedAt()?->format('Y-m-d H:i:s'),
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

    public function getUserIdentifier(): string
    {
        Assert::stringNotEmpty($this->email, 'User identifier (email) cannot be empty.');
        return $this->email;
    }

    public function getRoles(): array
    {
        $roles = array_map(fn(UserRoleMapping $mapping) => $mapping->getRole()->getRole(), $this->roles->toArray());
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function addRole(UserRole $role): void
    {
        if (!$this->roles->contains($role)) {
            $this->roles->add($role);
        }
    }

    public function removeRole(UserRole $role): void
    {
        $this->roles->removeElement($role);
    }

    /**
     * @param UserRole[] $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles->clear();
        foreach ($roles as $role) {
            $this->roles->add($role);
        }
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }


    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    public function setIsEmailConfirmed(bool $isEmailConfirmed): void
    {
        $this->isEmailConfirmed = $isEmailConfirmed;
    }

    public function getIsEmailConfirmed(): bool
    {
        return $this->isEmailConfirmed;
    }
}
