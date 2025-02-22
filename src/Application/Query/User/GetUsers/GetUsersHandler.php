<?php

declare(strict_types=1);

namespace App\Application\Query\User\GetUsers;

use App\Application\Query\Shared\Collection;
use App\Application\Query\Shared\Item;
use App\Domain\Core\User\Entity\User;
use App\Domain\Core\User\Repository\UserRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class GetUsersHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    )
    {
    }

    /**
     * @return Collection<Item<User>>
     */
    public function __invoke(GetUsersQuery $query): Collection
    {
        $result = $this->userRepository->page(
            $query->routeGenerator,
            $query->page,
            $query->perPage,
            $query->order,
            $query->sort,
            $query->uuidSearch,
            $query->emailSearch
        );
        $data = array_values($result->getData());
        return new Collection($query->page, $query->perPage, $result->getTotal(), $data);
    }
}
