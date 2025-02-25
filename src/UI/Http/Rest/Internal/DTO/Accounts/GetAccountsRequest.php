<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Internal\DTO\Accounts;

use App\Application\Query\Shared\Queries\BasePageQuery;
use Symfony\Component\Validator\Constraints as Assert;

class GetAccountsRequest
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
    #[Assert\Choice(choices: ['accountName', 'status', 'createdAt', 'updatedAt'], message: 'Invalid sort')]
    public $sort = BasePageQuery::SORT;

    #[Assert\Uuid(
        message: 'The UUID format is invalid.',
        strict: true
    )]
    public ?string $uuid = null;
}
