<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Implementation\QueryProcessor;

use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;

class PaginatedQueryProcessor implements QueryProcessorInterface
{
    /** @var Paginator<object>  */
    private Paginator $paginator;

    public function __construct(
        Query $query,
    ) {
        $this->paginator = new Paginator($query);
    }

    public function getEntities(): array
    {
        $entities = [];
        foreach ($this->paginator as $entity) {
            $entities[] = $entity;
        }
        return $entities;
    }
}