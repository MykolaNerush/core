<?php

declare(strict_types=1);

namespace App\Infrastructure\Core\User\Repository;

use App\Domain\Core\User\Entity\User;
use App\Domain\Core\User\Repository\UserRepositoryInterface;
use App\Infrastructure\Core\User\Transformer\FromEntity\UserTransformer;
use App\Infrastructure\Shared\Query\Repository\MysqlRepository;
use App\Domain\Core\Shared\Query\Dto\PaginatedData;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;

/**
 * @extends MysqlRepository<User>
 */
final class UserRepository extends MysqlRepository implements UserRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        /** @var class-string<User> */
        protected string $class = User::class
    )
    {
        parent::__construct($em);
    }

    public function page(
        callable       $routeGenerator,
        int            $page,
        int            $perPage,
        string         $order,
        string         $sort,
        ?UuidInterface $uuidSearch = null,
        string         $emailSearch = null
    ): PaginatedData
    {
        return $this->getFilteredPaginatedData(
            $this->getFilteredQueryBuilder($page, $perPage, $order, $sort, [
                ['uuid', $uuidSearch],
                ['email', ['LIKE', (null !== $emailSearch) ? '%' . $emailSearch . '%' : null], 'email'],
            ]),
            $routeGenerator,
            new UserTransformer(),
            'users'
        );
    }

    public function create(User $user): void
    {
        $this->em->persist($user);
        $this->em->flush();
    }

    public function delete(User $user, bool $force = false): void
    {
        $this->remove($user, $force);
    }
}
