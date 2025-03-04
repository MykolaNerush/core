<?php

declare(strict_types=1);

namespace App\Domain\Core\VideoComment\Entity;

use App\Domain\Core\User\Entity\User;
use App\Domain\Core\Video\Entity\Video;
use App\Domain\Core\VideoComment\Enum\CommentStatus;
use Broadway\ReadModel\SerializableReadModel;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity]
#[ORM\Table(name: 'video_comments')]
class VideoComment implements SerializableReadModel
{
    #[ORM\Id, ORM\GeneratedValue(strategy: 'NONE'), ORM\Column(type: 'uuid_binary', length: 16)]
    private UuidInterface $uuid;

    #[ORM\ManyToOne(targetEntity: Video::class, inversedBy: 'comments')]
    #[ORM\JoinColumn(name: 'video_uuid', referencedColumnName: 'uuid', nullable: false)]
    private Video $video;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_uuid', referencedColumnName: 'uuid', nullable: false)]
    private User $user;

    #[ORM\Column(type: Types::TEXT)]
    private string $comment;

    #[ORM\Column(type: Types::STRING, enumType: CommentStatus::class)]
    private CommentStatus $status;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?DateTimeImmutable $updatedAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?DateTimeImmutable $deletedAt = null;

    public function __construct(
        Video         $video,
        User          $user,
        string        $comment,
        CommentStatus $status = CommentStatus::PENDING
    )
    {
        $this->uuid = Uuid::uuid4();
        $this->video = $video;
        $this->user = $user;
        $this->comment = $comment;
        $this->status = $status;
        $this->createdAt = new DateTimeImmutable();
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }

    public function getDeletedAt(): ?DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(): void
    {
        $this->deletedAt = new DateTimeImmutable();
    }

    public function getVideo(): Video
    {
        return $this->video;
    }

    public function setVideo(Video $video): void
    {
        $this->video = $video;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function setComment(string $comment): void
    {
        $this->comment = $comment;
    }

    public function getStatus(): CommentStatus
    {
        return $this->status;
    }

    public function setStatus(CommentStatus $status): void
    {
        $this->status = $status;
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function deserialize(array $data): self
    {
        return new self(
            $data['video'],
            $data['user'],
            $data['comment'],
            CommentStatus::from($data['status']),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function serialize(): array
    {
        return [
            'uuid' => $this->uuid->toString(),
            'video' => $this->video->serialize(),
            'user' => $this->user->serialize(),
            'comment' => $this->comment,
            'status' => $this->status->value,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updatedAt?->format('Y-m-d H:i:s'),
            'deletedAt' => $this->deletedAt?->format('Y-m-d H:i:s'),
        ];
    }

    public function update(
        ?string        $comment = null,
        ?CommentStatus $status = null
    ): self
    {
        if ($comment !== null) {
            $this->setComment($comment);
        }
        if ($status !== null) {
            $this->setStatus($status);
        }
        $this->setUpdatedAt();
        return $this;
    }

    public function getId(): string
    {
        return Uuid::fromBytes($this->uuid->getBytes())->toString();
    }
}
