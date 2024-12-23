<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Internal\Controller;

use App\UI\Http\Rest\Shared\Controller\BaseQueryController;
use App\UI\Http\Rest\Shared\Response\BaseJsonApiFormatter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Application\Query\FullCollectionInterface;
use App\UI\Http\Rest\Internal\Response\JsonApiFormatter;

abstract class QueryController extends BaseQueryController
{
    /**
     * @var JsonApiFormatter
     */
    protected BaseJsonApiFormatter $formatter;

    public function __construct(
        JsonApiFormatter      $formatter,
        UrlGeneratorInterface $router,
        protected string      $dtoClass
    )
    {
        parent::__construct($formatter, $router);
    }

    protected function jsonFullCollection(FullCollectionInterface $fullCollection): JsonResponse
    {
        return new JsonResponse($this->formatter::fullCollection($fullCollection));
    }

    public function getDtoClass()
    {
        return $this->dtoClass;
    }


}
