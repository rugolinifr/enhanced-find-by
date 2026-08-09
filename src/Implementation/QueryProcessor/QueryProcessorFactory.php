<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Implementation\QueryProcessor;

use Doctrine\ORM\Query;
use Rugolinifr\EnhancedFindBy\Implementation\QueryBuilder\QueryBuilderData;

class QueryProcessorFactory
{
    public function createQueryProcessor(
        QueryBuilderData $data,
        Query $query,
    ): QueryProcessorInterface {
        if ($data->limit === null || !$this->hasJoin($data->where, $data->orderBy)) {
            return new SimpleQueryProcessor($query);
        }
        return new PaginatedQueryProcessor($query);
    }

    /**
     * @param array<string, mixed> $where
     * @param array<string, string> $orderBy
     */
    private function hasJoin(
        array $where = [],
        array $orderBy = [],
    ): bool {
        $propertyPathBag =  array_merge(array_keys($where), array_keys($orderBy));
        foreach ($propertyPathBag as $propertyPath) {
            if (str_contains($propertyPath, '.')) {
                return true;
            }
        }
        return false;
    }
}