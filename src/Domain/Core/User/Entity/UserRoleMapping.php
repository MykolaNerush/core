<?php

declare(strict_types=1);

namespace App\Domain\Core\User\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'user_roles_mapping')]
class UserRoleMapping
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid_binary', length: 16)]
    private UuidInterface $uuid;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'roles')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'uuid', onDelete: 'CASCADE')]
    private User $user;

    #[ORM\ManyToOne(targetEntity: UserRole::class, inversedBy: 'users')]
    #[ORM\JoinColumn(name: 'role_id', referencedColumnName: 'uuid', onDelete: 'CASCADE')]
    private UserRole $role;

    public function __construct(User $user, UserRole $role)
    {
        $this->uuid = Uuid::uuid4();
        $this->user = $user;
        $this->role = $role;
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getRole(): UserRole
    {
        return $this->role;
    }
}
