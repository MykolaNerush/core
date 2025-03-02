<?php

declare(strict_types=1);

namespace App\Infrastructure\Core\Video\Repository;

use App\Domain\Core\Video\Entity\Video;
use App\Domain\Core\Video\Repository\VideoRepositoryInterface;
use App\Infrastructure\Core\Video\Transformer\FromEntity\VideoTransformer;
use App\Infrastructure\Shared\Query\Repository\MysqlRepository;
use App\Domain\Core\Shared\Query\Dto\PaginatedData;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;

/**
 * @extends MysqlRepository<Video>
 */
final class VideoRepository extends MysqlRepository implements VideoRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        /** @var class-string<Video> */
        protected string                        $class = Video::class
    )
    {
        parent::__construct($this->em);
    }

    public function page(
        callable       $routeGenerator,
        int            $page,
        int            $perPage,
        string         $order,
        string         $sort,
        ?UuidInterface $uuidSearch = null,
        string         $title = null,
        string         $description = null,
        string         $filePath = null,
        string         $thumbnailPath = null,
        int            $duration = null,
    ): PaginatedData
    {
        return $this->getFilteredPaginatedData(
            $this->getFilteredQueryBuilder($page, $perPage, $order, $sort, [
                ['uuid', $uuidSearch],
                ['title', ['LIKE', (null !== $title) ? '%' . $title . '%' : null], 'title'],
                ['description', ['LIKE', (null !== $description) ? '%' . $description . '%' : null], 'description'],
                ['filePath', ['LIKE', (null !== $filePath) ? '%' . $filePath . '%' : null], 'filePath'],
                ['thumbnailPath', ['LIKE', (null !== $thumbnailPath) ? '%' . $thumbnailPath . '%' : null], 'thumbnailPath'],
                ['duration', ['=', (null !== $duration) ? $duration : null], 'duration'],
            ]),
            $routeGenerator,
            new VideoTransformer(),
            'videos'
        );
    }
}
