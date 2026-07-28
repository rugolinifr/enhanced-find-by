<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Implementation\QueryBuilder;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\QueryException as DoctrineOrmQueryException;
use Rugolinifr\EnhancedFindBy\Contract\EnhancedFindByExceptionInterface;
use Rugolinifr\EnhancedFindBy\Contract\EnhancedFindByInvalidArgumentException as EFBInvalidArgumentException;
use Rugolinifr\EnhancedFindBy\Implementation\OrderBy\OrderBy;
use Rugolinifr\EnhancedFindBy\Implementation\Shared\ComparisonInterface;
use Rugolinifr\EnhancedFindBy\Implementation\Shared\EnhancedFindByException;
use Rugolinifr\EnhancedFindBy\Implementation\Shared\Incrementor;
use Rugolinifr\EnhancedFindBy\Implementation\Shared\StrictFunction as SF;

class QueryBuilder
{
    public function __construct(
        private ComparisonFactory $comparisonFactory,
        private EntityManagerInterface $entityManager,
    ){
    }

    /**
     * @template T of object
     *
     * @param class-string<T> $from
     * @param array<string, mixed> $where
     * @param array<string, string> $orderBy
     * @return int|array<int, T>
     * @throws EFBInvalidArgumentException
     * @throws EnhancedFindByExceptionInterface
     */
    public function buildThenExecuteQuery(
        QueryTypeEnum $queryType,
        string $from,
        array $where = [],
        array $orderBy = [],
        ?int $limit = null,
        int $offset = 0,
    ): int|array {
        try {
            $incrementor = new Incrementor();
            $comparisons = $this->createEveryComparisons($where);
            $orderByClauses = $this->createEveryOrderByClauses($orderBy);
            $dql = $this->buildQueryDql($queryType, $from, $comparisons, $orderByClauses, $incrementor);
            $query = $this->entityManager->createQuery($dql);
            $query = $this->setQueryParameters($incrementor, $query);
            $query = $this->setLimit($limit, $offset, $query);
            return $queryType === QueryTypeEnum::SELECT ? $query->getResult() : $query->getSingleScalarResult();
        } catch (DoctrineOrmQueryException $e) { //@phpstan-ignore catch.neverThrown
            throw $this->convertDoctrineException($e);
        }
    }

    /**
     * @param array<string, mixed> $criteria
     * @return ComparisonInterface[]
     */
    private function createEveryComparisons(array $criteria): array
    {
        $comparisons = [];
        foreach ($criteria as $propertyPathAndOperator => $value) {
            $comparisons[] = $this->createNextComparison($propertyPathAndOperator, $value);
        }
        return $comparisons;
    }

    private function createNextComparison(mixed $propertyPathAndOperator, mixed $value): ComparisonInterface
    {
        if (!is_string($propertyPathAndOperator)) {
            throw new EFBInvalidArgumentException("The \$where parameter expects string as keys.");
        }
        return $this->comparisonFactory->createComparison($propertyPathAndOperator, $value);
    }

    /**
     * @param string[] $orderBy
     * @return OrderBy[]
     */
    private function createEveryOrderByClauses(array $orderBy): array
    {
        $orderByClauses = [];
        foreach ($orderBy as $propertyPath => $sortStrategy) {
            $orderByClauses[] = $this->createNextOrderBy($propertyPath, $sortStrategy);
        }
        return $orderByClauses;
    }

    private function createNextOrderBy(mixed $propertyPath, mixed $sortStrategy): OrderBy
    {
        if (!is_string($propertyPath)) {
            $msg = "The \$orderBy parameter expects string key.";
            throw new EFBInvalidArgumentException($msg);
        }
        if (!is_string($sortStrategy)) {
            $msg = "The \$orderBy parameter expects either 'ASC' or 'DESC' as sort strategy.";
            throw new EFBInvalidArgumentException($msg);
        }
        $sortStrategy = strtoupper($sortStrategy);
        if ($sortStrategy !== 'ASC' && $sortStrategy !== 'DESC') {
            $msg = "The \$orderBy parameter expects either 'ASC' or 'DESC' as sort strategy.";
            throw new EFBInvalidArgumentException($msg);
        }
        return $this->comparisonFactory->createOrderBy($propertyPath, $sortStrategy);
    }

    /**
     * @param OrderBy[] $orderByClauses
     * @param ComparisonInterface[] $comparisons
     */
    private function buildQueryDql(
        QueryTypeEnum $queryType,
        string $entityClassname,
        array $comparisons,
        array $orderByClauses,
        Incrementor $incrementor,
    ): string {
        $select = $queryType === QueryTypeEnum::SELECT ? 'SELECT e0' : 'SELECT COUNT(DISTINCT e0)';
        $from = "FROM $entityClassname e0";
        $joins = $this->buildJoins($comparisons, $orderByClauses, $incrementor);
        $where = $this->buildWhere($comparisons, $incrementor);
        $orderBy = $this->buildOrderBy($orderByClauses, $incrementor);
        return "$select\n$from\n$joins\n$where\n$orderBy";
    }

    /**
     * @param ComparisonInterface[] $comparisons
     * @param OrderBy[] $orderByClauses
     */
    private function buildJoins(
        array $comparisons,
        array $orderByClauses,
        Incrementor $incrementor,
    ): string {
        $joinSet = [];
        $joinsSource = array_merge($comparisons, $orderByClauses);
        foreach ($joinsSource as $joinSource) {
            $joins = $joinSource->getJoinsDql($incrementor);
            foreach ($joins as $join) {
                $joinSet[$join] = 1;
            }
        }
        $joinsResult = array_keys($joinSet);
        return implode("\n", $joinsResult);
    }

    /**
     * @param ComparisonInterface[] $comparisons
     */
    private function buildWhere(
        array $comparisons,
        Incrementor $incrementor,
    ): string {
        $where = '';
        foreach ($comparisons as $comparison) {
            $where .= $comparison->getWhereDql($incrementor);
            $where .= ' AND ';
        }
        $where = SF::preg_replace('/ AND $/', '', $where);
        return empty($where) ? '' : "WHERE $where";
    }

    /**
     * @param OrderBy[] $orderByClauses
     */
    private function buildOrderBy(array $orderByClauses, Incrementor $incrementor): string
    {
        $dql = '';
        foreach ($orderByClauses as $orderBy) {
            $dql .= $orderBy->getOrderByDql($incrementor) . ', ';
        }
        $dql = SF::preg_replace('/, $/', '', $dql);
        return empty($dql) ? '' : "ORDER BY $dql";
    }

    private function setQueryParameters(Incrementor $incrementor, Query $query): Query
    {
        foreach ($incrementor->getParameters() as $parameterName => $parameterValue) {
            $query->setParameter($parameterName, $parameterValue);
        }
        return $query;
    }

    private function setLimit(?int $limit, int $offset, Query $query): Query
    {
        if ($limit !== null) {
            if ($limit <= 0) {
                throw new EFBInvalidArgumentException('Invalid $limit value: it must be > 0.');
            }
            $query->setMaxResults($limit);
            if ($offset < 0) {
                throw new EFBInvalidArgumentException('Invalid $limit offset: it must be >= 0.');
            }
            $query->setFirstResult($offset);
        }
        return $query;
    }

    private function convertDoctrineException( //@phpstan-ignore method.unused
        DoctrineOrmQueryException $e
    ): EFBInvalidArgumentException|EnhancedFindByExceptionInterface {
        $pattern = '/has no field or association named ([a-zA-Z0-9_]+)/';
        if (1 === preg_match($pattern, $e->getMessage(), $matches)) {
            $msg = "One of the given parameter contains an invalid property path: \"$matches[1]\".";
            return new EFBInvalidArgumentException($msg, previous: $e);
        }
        $msg = "An error occurred while executing the DQL query: {$e->getMessage()}";
        return new EnhancedFindByException($msg, previous: $e);
    }
}
