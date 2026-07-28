<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Rugolinifr\EnhancedFindBy\Contract\EnhancedCountInterface;
use Rugolinifr\EnhancedFindBy\Contract\EnhancedFindByInterface;
use Rugolinifr\EnhancedFindBy\Implementation\Finder\EnhancedCount;
use Rugolinifr\EnhancedFindBy\Implementation\Finder\EnhancedFindBy;
use Rugolinifr\EnhancedFindBy\Implementation\QueryBuilder\ComparisonFactory;
use Rugolinifr\EnhancedFindBy\Implementation\QueryBuilder\QueryBuilder;
use Rugolinifr\EnhancedFindBy\Implementation\Shared\AliasedPropertyProvider;
use Rugolinifr\EnhancedFindBy\Implementation\Shared\JoinClauseProvider;

class EnhancedFindByFactory
{
    private ?QueryBuilder $queryBuilder = null;

    public function createEnhancedFindBy(EntityManagerInterface $entityManager): EnhancedFindByInterface
    {
        if ($this->queryBuilder === null) {
            $this->queryBuilder = $this->createQueryBuilder($entityManager);
        }
        return new EnhancedFindBy($this->queryBuilder);
    }

    public function createEnhancedCount(EntityManagerInterface $entityManager): EnhancedCountInterface
    {
        if ($this->queryBuilder === null) {
            $this->queryBuilder = $this->createQueryBuilder($entityManager);
        }
        return new EnhancedCount($this->queryBuilder);
    }

    private function createQueryBuilder(EntityManagerInterface $entityManager): QueryBuilder
    {
        $joinClauseProvider = new JoinClauseProvider();
        $aliasedPropertyProvider = new AliasedPropertyProvider();
        $comparisonFactory = new ComparisonFactory($entityManager, $joinClauseProvider, $aliasedPropertyProvider);
        return new QueryBuilder($comparisonFactory, $entityManager);
    }
}
