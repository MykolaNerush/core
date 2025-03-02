<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Internal\DTO\Video;

use App\Application\Query\Shared\Queries\BasePageQuery;
use Symfony\Component\Validator\Constraints as Assert;

class GetVideosRequest
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
    #[Assert\Choice(choices: ['title', 'status', 'createdAt', 'updatedAt'], message: 'Invalid sort')]
    public $sort = BasePageQuery::SORT;

    #[Assert\Uuid(
        message: 'The UUID format is invalid.',
        strict: true
    )]
    public ?string $uuid = null;

    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'The title must be at least {{ limit }} characters long.',
        maxMessage: 'The title cannot be longer than {{ limit }} characters.'
    )]
    public ?string $title = null;

    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'The description must be at least {{ limit }} characters long.',
        maxMessage: 'The description cannot be longer than {{ limit }} characters.'
    )]
    public ?string $description = null;

    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'The filePath must be at least {{ limit }} characters long.',
        maxMessage: 'The filePath cannot be longer than {{ limit }} characters.'
    )]
    public ?string $filePath = null;

    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'The thumbnailPath must be at least {{ limit }} characters long.',
        maxMessage: 'The thumbnailPath cannot be longer than {{ limit }} characters.'
    )]
    public ?string $thumbnailPath = null;
}
