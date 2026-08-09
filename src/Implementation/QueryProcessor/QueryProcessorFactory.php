<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Implementation\QueryProcessor;

use Doctrine\ORM\Query;

class QueryProcessorFactory
{
    /**
     * @param array<string, string> $orderBy
     * @param array<string, mixed> $where
     */
    public function createQueryProcessor(
        Query $query,
        array $where = [],
        array $orderBy = [],
        ?int $limit = null,
    ): QueryProcessorInterface {
        if ($limit === null || !$this->hasJoin($where, $orderBy)) {
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