<?php

declare(strict_types=1);

namespace App\Domain\Core\UserVideo\Entity;

use App\Domain\Core\UserVideo\Enum\Role;
use App\Domain\Core\User\Entity\User;
use App\Domain\Core\Video\Entity\Video;
use App\Domain\Shared\Entity\OwnableInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity, ORM\Table(name: 'user_videos')]
class UserVideo implements OwnableInterface
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'userVideos')]
    #[ORM\JoinColumn(name: 'user_uuid', referencedColumnName: 'uuid', nullable: false, onDelete: 'CASCADE')]
    private User $user;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Video::class, inversedBy: 'userVideos')]
    #[ORM\JoinColumn(name: 'video_uuid', referencedColumnName: 'uuid', nullable: false, onDelete: 'CASCADE')]
    private Video $video;

    #[ORM\Column(type: Types::STRING, enumType: Role::class)]
    private Role $role = Role::VIEWER;

    public function __construct(User $user, Video $video, Role $role = Role::VIEWER)
    {
        $this->user = $user;
        $this->video = $video;
        $this->role = $role;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getVideo(): Video
    {
        return $this->video;
    }

    public function setVideo(Video $video): self
    {
        $this->video = $video;
        return $this;
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    public function setRole(Role $role): void
    {
        $this->role = $role;
    }

    public function getOwnerId(): string
    {
        return $this->user->getUuid()->toString();
    }

}
