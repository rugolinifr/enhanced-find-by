<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Implementation\QueryBuilder;

use Rugolinifr\EnhancedFindBy\Implementation\OrderBy\OrderBy;
use Rugolinifr\EnhancedFindBy\Implementation\Shared\ComparisonInterface;
use Rugolinifr\EnhancedFindBy\Implementation\Shared\EnhancedImplementationInvalidArgumentException as ImplementationInvalidArgumentException;
use Rugolinifr\EnhancedFindBy\Implementation\Shared\Incrementor;
use Rugolinifr\EnhancedFindBy\Implementation\Shared\StrictFunction as SF;

class DqlBuilder
{
    /**
     * @param OrderBy[] $orderByClauses
     * @param ComparisonInterface[] $comparisons
     *
     * @throws ImplementationInvalidArgumentException
     */
    public function buildQueryDql(
        QueryBuilderData $data,
        array $comparisons,
        array $orderByClauses,
        Incrementor $incrementor,
    ): string {
        $select = $this->buildSelect($data->queryType);
        $from = "FROM $data->from e0";
        $joins = $this->buildJoins($comparisons, $orderByClauses, $incrementor);
        $where = $this->buildWhere($comparisons, $incrementor);
        $orderBy = $this->buildOrderBy($orderByClauses, $incrementor);
        return "$select\n$from\n$joins\n$where\n$orderBy";
    }

    private function buildSelect(
        QueryTypeEnum $queryType,
    ): string {
        if ($queryType === QueryTypeEnum::COUNT) {
            return  'SELECT COUNT(DISTINCT e0)';
        }
        return 'SELECT e0';
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
     *
     * @throws ImplementationInvalidArgumentException
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
    private function buildOrderBy(
        array $orderByClauses,
        Incrementor $incrementor,
    ): string {
        $dql = '';
        foreach ($orderByClauses as $orderBy) {
            $dql .= $orderBy->getOrderByDql($incrementor) . ', ';
        }
        $dql = SF::preg_replace('/, $/', '', $dql);
        return empty($dql) ? '' : "ORDER BY $dql";
    }
}
