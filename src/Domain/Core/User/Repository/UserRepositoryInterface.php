<?php

declare(strict_types=1);

namespace App\Domain\Core\User\Repository;

use App\Domain\Core\User\Entity\User;
use Ramsey\Uuid\UuidInterface;
use App\Domain\Core\Shared\Query\Dto\PaginatedData;

interface UserRepositoryInterface
{
    public function getByUuid(UuidInterface $uuid): mixed;
    public function update(User $user): void;
    public function create(User $user): void;
    public function delete(User $user, bool $force = false): void;
    public function page(
        callable      $routeGenerator,
        int           $page,
        int           $perPage,
        string        $order,
        string        $sort,
        UuidInterface $uuidSearch = null,
        string        $emailSearch = null
    ): PaginatedData;
}
