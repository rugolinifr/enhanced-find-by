<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Implementation\Finder;

use Rugolinifr\EnhancedFindBy\Contract\EnhancedCountInterface;
use Rugolinifr\EnhancedFindBy\Contract\EnhancedCountInvalidArgumentException;
use Rugolinifr\EnhancedFindBy\Contract\EnhancedFindByExceptionInterface;
use Rugolinifr\EnhancedFindBy\Contract\EnhancedFindByInvalidArgumentException as EFBInvalidArgumentException;
use Rugolinifr\EnhancedFindBy\Implementation\QueryBuilder\QueryBuilder;
use Rugolinifr\EnhancedFindBy\Implementation\QueryBuilder\QueryTypeEnum;

class EnhancedCount implements EnhancedCountInterface
{
    public function __construct(
        private QueryBuilder $queryBuilder,
    ) {
    }

    public function count(
        string $from,
        array $where = [],
    ): int {
        try {
            //@phpstan-ignore return.type
            return $this->queryBuilder->buildThenExecuteQuery(QueryTypeEnum::COUNT, $from, $where);
        } catch (EFBInvalidArgumentException $e) {
            throw new EnhancedCountInvalidArgumentException($e->getMessage(), previous: $e);
        } catch (EnhancedFindByExceptionInterface $e) {
            throw new EnhancedCountException($e->getMessage(), previous: $e);
        }
    }
}