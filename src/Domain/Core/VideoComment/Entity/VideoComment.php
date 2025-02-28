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

    #[ORM\Column(Types::TEXT)]
    private string $content;

    #[ORM\Column(type: Types::STRING, enumType: CommentStatus::class)]
    private CommentStatus $status;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?DateTimeImmutable $updatedAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?DateTimeImmutable $deletedAt = null;

    public function __construct(
        UuidInterface $uuid,
        Video         $video,
        User          $user,
        string        $content,
        CommentStatus $status= CommentStatus::PENDING
    )
    {
        $this->uuid = $uuid;
        $this->video = $video;
        $this->user = $user;
        $this->content = $content;
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

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
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
        $comment = new self();
        $comment->uuid = Uuid::fromString($data['uuid']);
        $comment->video = $data['video'];
        $comment->user = $data['user'];
        $comment->content = $data['content'];
        $comment->status = CommentStatus::from($data['status']);
        $comment->createdAt = new DateTimeImmutable($data['createdAt']);
        $comment->updatedAt = isset($data['updatedAt']) ? new DateTimeImmutable($data['updatedAt']) : null;
        $comment->deletedAt = isset($data['deletedAt']) ? new DateTimeImmutable($data['deletedAt']) : null;
        return $comment;
    }

    /**
     * @return array<string, mixed>
     */
    public function serialize(): array
    {
        return [
            'uuid' => $this->uuid->toString(),
            'video' => $this->video->getUuid()->toString(),
            'user' => $this->user->getUuid()->toString(),
            'content' => $this->content,
            'status' => $this->status->value,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updatedAt?->format('Y-m-d H:i:s'),
            'deletedAt' => $this->deletedAt?->format('Y-m-d H:i:s'),
        ];
    }

    public function update(
        ?string        $content = null,
        ?CommentStatus $status = null
    ): self
    {
        if ($content !== null) {
            $this->setContent($content);
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
