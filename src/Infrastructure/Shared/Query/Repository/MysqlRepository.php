<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Query\Repository;

use App\Domain\Core\Shared\Query\Dto\PaginatedData;
use App\Infrastructure\Shared\Exception\NotFoundException;
use App\Infrastructure\Shared\Serializer\JsonApiSerializer;
use Broadway\ReadModel\SerializableReadModel;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use League\Fractal\Manager;
use League\Fractal\Pagination\DoctrinePaginatorAdapter;
use League\Fractal\Resource\Collection;
use Ramsey\Uuid\UuidInterface;

/**
 * @template T of object
 */
abstract class MysqlRepository
{
    /**
     * @var class-string<T>
     */
    protected string $class;

    protected string $alias;

    /**
     * @var EntityRepository<T>
     */
    protected EntityRepository $repository;

    /**
     * @throws \ReflectionException
     */
    public function __construct(protected EntityManagerInterface $entityManager)
    {
        $this->setRepository($this->class);
        $this->alias = lcfirst((new \ReflectionClass($this->class))->getShortName());
    }

    public function remove(SerializableReadModel $model, bool $force = false): void
    {
        if ($force) {
            $this->entityManager->remove($model);
        } elseif (method_exists($model, 'setDeletedAt')) {
            $model->setDeletedAt();
            $this->update($model);
        }

        $this->flush();
    }

    public function create(SerializableReadModel $model): void
    {
        $this->persist($model);
        $this->flush();
    }

    public function clear(): void
    {
        $this->entityManager->clear();
    }

    public function persist(SerializableReadModel $model): void
    {
        $this->entityManager->persist($model);
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }

    protected function oneOrException(QueryBuilder $queryBuilder): mixed
    {
        $model = $queryBuilder
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $model) {
            throw new NotFoundException(ucfirst($this->alias));
        }

        return $model;
    }

    protected function singleScalarOrException(QueryBuilder $queryBuilder): mixed
    {
        $result = $queryBuilder
            ->getQuery()
            ->getSingleScalarResult();

        if (null === $result) {
            throw new NotFoundException(ucfirst($this->alias));
        }

        return $result;
    }

    protected function allOrException(QueryBuilder $queryBuilder): mixed
    {
        $model = $queryBuilder
            ->getQuery()
            ->getResult();

        if (null === $model) {
            throw new NotFoundException(ucfirst($this->alias));
        }

        return $model;
    }

    /**
     * @return array<int, mixed>
     */
    protected function allAsArrayOrException(QueryBuilder $queryBuilder): array
    {
        return $queryBuilder
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * @param UuidInterface[] $uuids
     */
    public function getByUuids(array $uuids): mixed
    {
        return $this->getQueryBuilder()
            ->where($this->alias . '.uuid in (:uuids)')
            ->setParameter('uuids', array_map(fn(UuidInterface $uuid): string => $uuid->getBytes(), $uuids))
            ->getQuery()
            ->getResult();
    }

    /**
     * @param class-string<T> $model
     * @return void
     */
    private function setRepository(string $model): void
    {
        $this->repository = $this->entityManager->getRepository($model);
    }

    public function getQueryBuilder(bool $withDeleted = false): QueryBuilder
    {
        $queryBuilder = $this->repository->createQueryBuilder($this->alias);
        if (!$withDeleted && property_exists($this->class, 'deletedAt')) {
            $queryBuilder->where($this->alias . '.deletedAt is null');
        }

        return $queryBuilder;
    }

    public function getQueryBuilderByUuid(UuidInterface $uuid): QueryBuilder
    {
        return $this->getQueryBuilder()
            ->andWhere($this->alias . '.uuid = :uuid')
            ->setParameter('uuid', $uuid->getBytes());
    }

    protected function getFilteredPaginatedData(
        QueryBuilder $queryBuilder,
        callable     $routeGenerator,
        mixed        $transformer,
        string       $resourceKey = null,
        bool         $fetchJoinCollection = true
    ): PaginatedData
    {
        $paginator = new Paginator($queryBuilder, $fetchJoinCollection);
        $paginatorAdapter = new DoctrinePaginatorAdapter($paginator, $routeGenerator);
        $resource = new Collection($paginator, $transformer, $resourceKey);
        $resource->setPaginator($paginatorAdapter);
        $fractal = new Manager();
        $fractal->setSerializer(new JsonApiSerializer());
        /** @var array<string, string|mixed> $data */
        $data = $fractal->createData($resource)->toArray();
        return new PaginatedData($data);
    }

    /**
     * @param int $page
     * @param int $perPage
     * @param string $order
     * @param string $sort
     * @param array<int, array<int, string|string[]|mixed>> $filters
     * @param QueryBuilder|null $queryBuilder
     * @return QueryBuilder
     *
     * Each filter is an array with the following structure:
     * ```php
     * [
     *     'field',
     *     'value',
     *     'paramName'
     * ] OR [
     *     'field',
     *     ['operator', 'value'], ['IS NULL'], ['LIKE', null]
     *     'paramName'
     * ] OR [
     *     ['field', 'alias'],
     *     'value',
     *     'paramName'
     * ]
     * ```
     */
    public function getFilteredQueryBuilder(
        int          $page,
        int          $perPage,
        string       $order,
        string       $sort,
        array        $filters = [],
        QueryBuilder $queryBuilder = null
    ): QueryBuilder
    {
        $queryBuilder = $queryBuilder ?? $this->getQueryBuilder();
        $this->addConditions($queryBuilder, $filters);
        $queryBuilder->orderBy($this->alias . '.' . $sort, $order);
        $queryBuilder
            ->setFirstResult($perPage * ($page - 1))
            ->setMaxResults($perPage);
        return $queryBuilder;
    }

    /**
     * @param array<int, array<int, string|string[]>> $filters
     */
    protected function addConditions(QueryBuilder $queryBuilder, array $filters): void
    {
        foreach ($filters as $filter) {
            [$field, $value] = $filter;
            if (is_array($field)) {
                $alias = $field[1];
                $fieldName = $field[0];
            } else {
                $alias = $this->alias;
                $fieldName = $field;
            }
            $paramName = !empty($filter[2]) ? $filter[2] : $fieldName;
            $paramName = is_string($paramName) ? $paramName : '';
            if (is_array($value)) {
                if (!array_key_exists(1, $value)) {
                    $queryBuilder->andWhere(sprintf('%s.%s %s', $alias, $fieldName, $value[0]));
                } else {
                    $this->addFilter($queryBuilder, $alias, $fieldName, $value[0], $value[1], $paramName);
                }
            } else {
                $this->addFilter($queryBuilder, $alias, $fieldName, '=', $value, $paramName);
            }
        }
    }

    private function addFilter(
        QueryBuilder $queryBuilder,
        string       $alias,
        string       $field,
        string       $operator,
        mixed        $value,
        string       $paramName
    ): void
    {
        if (null === $value) {
            return;
        }
        if (is_string($value) || is_object($value)) {
            if (method_exists($value, 'isEmpty') && is_object($value) && $value->isEmpty()) {
                return;
            }
        }
        if ($value instanceof UuidInterface) {
            $value = $value->getBytes();
        }
        if (str_contains(strtolower($operator), 'in')) {
            $format = '%s.%s %s (:%s)';
        } else {
            $format = '%s.%s %s :%s';
        }
        $queryBuilder
            ->andWhere(
                sprintf(
                    $format,
                    $alias,
                    $field,
                    $operator,
                    $paramName
                )
            )
            ->setParameter($paramName, $value);
    }

    protected function orderBy(
        QueryBuilder $queryBuilder,
        string       $order,
        string       $sort
    ): void
    {
        $queryBuilder->orderBy($this->alias . '.' . $sort, $order);
    }

    /**
     * @param array<string, mixed> $filters
     *
     */
    public function findOneBy(array $filters): mixed
    {
        $queryBuilder = $this->getQueryBuilder(true);

        foreach ($filters as $field => $value) {
            $paramName = str_replace('.', '_', $field);
            if (is_array($value)) {
                $queryBuilder->andWhere($this->alias . '.' . $field . ' IN (:' . $paramName . ')');
            } else {
                $queryBuilder->andWhere($this->alias . '.' . $field . ' = :' . $paramName);
            }
            $queryBuilder->setParameter($paramName, $value);
        }
        return $queryBuilder
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function update(SerializableReadModel $model): void
    {
        $this->entityManager->persist($model);
        $this->entityManager->flush();
    }

    public function getByUuid(UuidInterface $uuid): mixed
    {
        $queryBuilder = $this->getQueryBuilderByUuid($uuid);
        return $this->oneOrException($queryBuilder);
    }
}
