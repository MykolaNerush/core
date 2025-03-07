<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Internal\DTO\VideoComments;

use App\Application\Query\Shared\Queries\BasePageQuery;
use Symfony\Component\Validator\Constraints as Assert;

class GetVideoCommentsRequest
{
    #[Assert\Positive(message: 'Invalid page parameter')]
    public int $page = BasePageQuery::PAGE;

    #[Assert\Positive(message: 'Invalid per page')]
    public int $perPage = BasePageQuery::PER_PAGE;

    #[Assert\Choice(choices: ['ASC', 'DESC'], message: 'Invalid order')]
    public string $order = BasePageQuery::ORDER;

    #[Assert\Choice(choices: ['status', 'createdAt', 'updatedAt'], message: 'Invalid sort')]
    public string $sort = BasePageQuery::SORT;

    #[Assert\Uuid(
        message: 'The UUID format is invalid.',
        strict: true
    )]
    public ?string $uuid = null;

    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'The comment must be at least {{ limit }} characters long.',
        maxMessage: 'The comment cannot be longer than {{ limit }} characters.'
    )]
    public ?string $comment = null;
}
