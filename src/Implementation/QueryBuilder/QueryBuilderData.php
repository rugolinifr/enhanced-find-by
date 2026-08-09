<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Implementation\QueryBuilder;

class QueryBuilderData
{
    /**
     * @template T of object
     *
     * @param class-string<T> $from
     * @param array<string, mixed> $where
     * @param array<string, string> $orderBy
     */
    public function __construct(
        public QueryTypeEnum $queryType,
        public string $from,
        public array $where = [],
        public array $orderBy = [],
        public ?int $limit = null,
        public int $offset = 0,
    ) {
    }
}
