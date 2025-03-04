<?php

declare(strict_types=1);

namespace App\Infrastructure\Core\VideoComments\Repository;

use App\Domain\Core\VideoComment\Entity\VideoComment;
use App\Domain\Core\VideoComment\Repository\VideoCommentsRepositoryInterface;
use App\Infrastructure\Core\VideoComments\Transformer\FromEntity\VideoCommentsTransformer;
use App\Infrastructure\Shared\Query\Repository\MysqlRepository;
use App\Domain\Core\Shared\Query\Dto\PaginatedData;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;

/**
 * @extends MysqlRepository<VideoComment>
 */
final class VideoCommentsRepository extends MysqlRepository implements VideoCommentsRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        /** @var class-string<VideoComment> */
        protected string                        $class = VideoComment::class
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
        ?string        $comment = null,
    ): PaginatedData
    {
        return $this->getFilteredPaginatedData(
            $this->getFilteredQueryBuilder($page, $perPage, $order, $sort, [
                ['uuid', $uuidSearch],
                ['comment', ['LIKE', (null !== $comment) ? '%' . $comment . '%' : null], 'comment'],
            ]),
            $routeGenerator,
            new VideoCommentsTransformer(),
            'videoComment'
        );
    }
}
