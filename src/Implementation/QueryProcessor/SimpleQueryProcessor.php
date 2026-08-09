<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Implementation\QueryProcessor;

use Doctrine\ORM\Query;

class SimpleQueryProcessor implements QueryProcessorInterface
{
    public function __construct(
        private Query $query,
    ) {
    }

    public function getEntities(): array
    {
        return $this->query->getResult();
    }
}
