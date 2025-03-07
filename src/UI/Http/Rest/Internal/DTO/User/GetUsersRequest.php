<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Internal\DTO\User;

use App\Application\Query\Shared\Queries\BasePageQuery;
use Symfony\Component\Validator\Constraints as Assert;

class GetUsersRequest
{
    #[Assert\Positive(message: 'Invalid page parameter')]
    public int $page = BasePageQuery::PAGE;

    #[Assert\Positive(message: 'Invalid per page')]
    public int $perPage = BasePageQuery::PER_PAGE;

    #[Assert\Choice(choices: ['ASC', 'DESC'], message: 'Invalid order')]
    public string $order = BasePageQuery::ORDER;

    #[Assert\Choice(choices: ['name', 'status', 'createdAt', 'updatedAt'], message: 'Invalid sort')]
    public string $sort = BasePageQuery::SORT;

    #[Assert\Uuid(
        message: 'The UUID format is invalid.',
        strict: true
    )]
    public ?string $uuid = null;

    #[Assert\Email(message: 'The email format is invalid.')]
    public ?string $email = null;
}
