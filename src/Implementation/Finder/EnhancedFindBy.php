<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Implementation\Finder;

use Rugolinifr\EnhancedFindBy\Contract\EnhancedFindByInterface;
use Rugolinifr\EnhancedFindBy\Implementation\QueryBuilder\QueryBuilder;
use Rugolinifr\EnhancedFindBy\Implementation\QueryBuilder\QueryTypeEnum;

class EnhancedFindBy implements EnhancedFindByInterface
{

    public function __construct(
        private QueryBuilder $queryBuilder,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function findBy(
        string $from,
        array $where = [],
        array $orderBy = [],
        ?int $limit = null,
        int $offset = 0,
    ): array {
        return $this->queryBuilder->buildThenExecuteQuery( //@phpstan-ignore return.type
            QueryTypeEnum::SELECT,
            $from,
            $where,
            $orderBy,
            $limit,
            $offset,
        );
    }
}
