<?php

declare(strict_types=1);

namespace App\Domain\Core\Video\Entity;

use App\Domain\Core\UserVideo\Entity\UserVideo;
use App\Domain\Core\VideoComment\Entity\VideoComment;
use App\Domain\Shared\Entity\TimestampableEntity;
use Broadway\ReadModel\SerializableReadModel;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;
use App\Domain\Core\Video\Enum\Status;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity, ORM\Table(name: 'videos')]
class Video extends TimestampableEntity implements SerializableReadModel
{
    #[ORM\Id, ORM\GeneratedValue(strategy: 'NONE'), ORM\Column(type: 'uuid_binary', length: 16)]
    private UuidInterface $uuid;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $title;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $description = null;
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $filePath = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $thumbnailPath = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $duration = 0;

    #[ORM\Column(type: Types::STRING, enumType: Status::class)]
    private Status $status;

    #[ORM\OneToMany(targetEntity: VideoComment::class, mappedBy: 'video')]
    private Collection $comments;

    #[ORM\OneToMany(targetEntity: UserVideo::class, mappedBy: 'video')]
    private Collection $userVideos;

    public function __construct(
        string  $title,
        ?string $description = null,
        ?string $filePath = null,
        ?string $thumbnailPath = null,
        ?int    $duration = 0,
        Status  $status = Status::DRAFT,
    )
    {
        $this->uuid = Uuid::uuid4();
        $this->title = $title;
        $this->description = $description;
        $this->filePath = $filePath;
        $this->thumbnailPath = $thumbnailPath;
        $this->duration = $duration;
        $this->status = $status;
        $this->createdAt = new DateTimeImmutable();
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    public function setFilePath(?string $filePath): void
    {
        $this->filePath = $filePath;
    }

    public function getThumbnailPath(): ?string
    {
        return $this->thumbnailPath;
    }

    public function setThumbnailPath(?string $thumbnailPath): void
    {
        $this->thumbnailPath = $thumbnailPath;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): void
    {
        $this->duration = $duration;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function setStatus(Status $status): void
    {
        $this->status = $status;
    }

    public function getId(): string
    {
        return $this->uuid->toString();
    }

    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function getUserVideos(): Collection
    {
        return $this->userVideos;
    }


    /**
     * @param array<string, mixed> $data
     */
    public static function deserialize(array $data): self
    {
        return new self(
            $data['title'],
            $data['description'] ?? null,
            $data['filePath'] ?? null,
            $data['thumbnailPath'] ?? null,
            $data['duration'] ?? 0,
            Status::from($data['status']),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function serialize(): array
    {
        return [
            'uuid' => $this->getUuid()->toString(),
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'filePath' => $this->getFilePath(),
            'thumbnailPath' => $this->getThumbnailPath(),
            'duration' => $this->getDuration(),
            'status' => $this->getStatus()->label(),
            'timestamps' => [
                'createdAt' => $this->getCreatedAt()?->format('Y-m-d H:i:s'),
                'updatedAt' => $this->getUpdatedAt()?->format('Y-m-d H:i:s'),
                'deletedAt' => $this->getDeletedAt()?->format('Y-m-d H:i:s'),
            ],
        ];
    }

    public function update(
        ?string $title = null,
        ?string $description = null,
        ?string $filePath = null,
        ?string $thumbnailPath = null,
        ?int    $duration = null,
        ?Status $status = null
    ): void
    {
        if ($title !== null) {
            $this->setTitle($title);
        }
        if ($description !== null) {
            $this->setDescription($description);
        }
        if ($filePath !== null) {
            $this->setFilePath($filePath);
        }
        if ($thumbnailPath !== null) {
            $this->setThumbnailPath($thumbnailPath);
        }
        if ($duration !== null) {
            $this->setDuration($duration);
        }
        if ($status !== null) {
            $this->setStatus($status);
        }
        $this->setUpdatedAt();
    }
}
