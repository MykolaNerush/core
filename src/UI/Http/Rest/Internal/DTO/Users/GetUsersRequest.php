<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Internal\DTO\Users;

use App\Application\Query\Shared\Queries\BasePageQuery;
use Symfony\Component\Validator\Constraints as Assert;

class GetUsersRequest
{
    /**
     * @var int
     */
    #[Assert\Positive(message: 'Invalid page parameter')]
    public $page = BasePageQuery::PAGE;

    /**
     * @var int
     */
    #[Assert\Positive(message: 'Invalid per page')]
    public $perPage = BasePageQuery::PER_PAGE;

    /**
     * @var string
     */
    #[Assert\Choice(choices: ['ASC', 'DESC'], message: 'Invalid order')]
    public $order = BasePageQuery::ORDER;

    /**
     * @var string
     */
    #[Assert\Choice(choices: ['name', 'status', 'createdAt', 'updatedAt'], message: 'Invalid sort')]
    public $sort = BasePageQuery::SORT;

    #[Assert\Uuid]
    public ?string $uuid = null;

    #[Assert\Email]
    public ?string $email = null;
}
