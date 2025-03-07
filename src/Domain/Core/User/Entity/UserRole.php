<?php

declare(strict_types=1);

namespace App\Domain\Core\User\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity, ORM\Table(name: 'user_roles')]
class UserRole
{
    #[ORM\Id, ORM\Column(type: Types::STRING, length: 50)]
    private string $role;

    #[ORM\OneToMany(targetEntity: UserRoleMapping::class, mappedBy: 'role')]
    private Collection $users;


    public function __construct(string $role)
    {
        $this->role = $role;
    }

    public function getRole(): string
    {
        return $this->role;
    }
}
