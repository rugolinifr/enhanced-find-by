<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Implementation\Finder;

use Rugolinifr\EnhancedFindBy\Contract\EnhancedCountInterface;
use Rugolinifr\EnhancedFindBy\Contract\EnhancedCountInvalidArgumentException;
use Rugolinifr\EnhancedFindBy\Contract\EnhancedFindByInterface;
use Rugolinifr\EnhancedFindBy\Contract\EnhancedFindByInvalidArgumentException;
use Rugolinifr\EnhancedFindBy\Implementation\QueryBuilder\QueryBuilder;
use Rugolinifr\EnhancedFindBy\Implementation\QueryBuilder\QueryBuilderData;
use Rugolinifr\EnhancedFindBy\Implementation\QueryBuilder\QueryTypeEnum;
use Rugolinifr\EnhancedFindBy\Implementation\Shared\EnhancedImplementationException;
use Rugolinifr\EnhancedFindBy\Implementation\Shared\EnhancedImplementationInvalidArgumentException as ImplementationInvalidArgumentException;

class EnhancedImplementation implements EnhancedFindByInterface, EnhancedCountInterface
{

    public function __construct(
        private QueryBuilder $queryBuilder,
    ) {
    }

    public function findBy(
        string $from,
        array $where = [],
        array $orderBy = [],
        ?int $limit = null,
        int $offset = 0,
    ): array {
        try {
            $data = new QueryBuilderData(
                QueryTypeEnum::SELECT,
                $from,
                $where,
                $orderBy,
                $limit,
                $offset,
            );
            return $this->queryBuilder->buildThenExecuteQuery($data); //@phpstan-ignore return.type
        } catch (ImplementationInvalidArgumentException $e) {
            throw new EnhancedFindByInvalidArgumentException($e->getMessage(), previous: $e);
        } catch (EnhancedImplementationException $e) {
            throw new EnhancedFindByException($e->getMessage(), previous: $e);
        }
    }

    public function count(
        string $from,
        array $where = [],
    ): int {
        try {
            $data = new QueryBuilderData(QueryTypeEnum::COUNT, $from, $where);
            return $this->queryBuilder->buildThenExecuteQuery($data); //@phpstan-ignore return.type
        } catch (ImplementationInvalidArgumentException $e) {
            throw new EnhancedCountInvalidArgumentException($e->getMessage(), previous: $e);
        } catch (EnhancedImplementationException $e) {
            throw new EnhancedCountException($e->getMessage(), previous: $e);
        }
    }
}
