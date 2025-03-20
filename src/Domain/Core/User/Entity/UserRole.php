<?php

declare(strict_types=1);

namespace App\Domain\Core\User\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Uuid;
//use Doctrine\Common\Collections\Collection;

#[ORM\Entity, ORM\Table(name: 'user_roles')]
#[ORM\UniqueConstraint(name: 'UNIQ_USER_ROLE_ROLE', columns: ['role'])]
class UserRole
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid_binary', length: 16)]
    private UuidInterface $uuid;

    #[ORM\Column(type: Types::STRING, length: 50)]
    private string $role;

//    #[ORM\OneToMany(targetEntity: UserRoleMapping::class, mappedBy: 'role')]
//    private Collection $users;


    public function __construct(string $role)
    {
        $this->uuid = Uuid::uuid4();
        $this->role = $role;
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getRole(): string
    {
        return $this->role;
    }
}
