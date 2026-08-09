<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Rugolinifr\EnhancedFindBy\Contract\EnhancedCountInterface;
use Rugolinifr\EnhancedFindBy\Contract\EnhancedFindByInterface;
use Rugolinifr\EnhancedFindBy\Implementation\Finder\EnhancedImplementation;
use Rugolinifr\EnhancedFindBy\Implementation\QueryBuilder\ComparisonFactory;
use Rugolinifr\EnhancedFindBy\Implementation\QueryBuilder\QueryBuilder;
use Rugolinifr\EnhancedFindBy\Implementation\Shared\AliasedPropertyProvider;
use Rugolinifr\EnhancedFindBy\Implementation\Shared\JoinClauseProvider;

class EnhancedFindByFactory
{
    private ?EnhancedImplementation $implementation = null;

    public function createEnhancedFindBy(
        EntityManagerInterface $entityManager,
        bool $fromCache = true,
    ): EnhancedFindByInterface {
        return $this->createEnhancedImplementation($entityManager, $fromCache);
    }

    public function createEnhancedCount(
        EntityManagerInterface $entityManager,
        bool $fromCache = true,
    ): EnhancedCountInterface {
        return $this->createEnhancedImplementation($entityManager, $fromCache);
    }

    private function createEnhancedImplementation(
        EntityManagerInterface $entityManager,
        bool $fromCache = true,
    ): EnhancedImplementation {
        if ($fromCache === false) {
            return $this->createImplementation($entityManager);
        }
        if ($this->implementation === null) {
            $this->implementation = $this->createImplementation($entityManager);
        }
        return $this->implementation;
    }

    private function createImplementation(EntityManagerInterface $entityManager): EnhancedImplementation
    {
        $joinClauseProvider = new JoinClauseProvider();
        $aliasedPropertyProvider = new AliasedPropertyProvider();
        $comparisonFactory = new ComparisonFactory($entityManager, $joinClauseProvider, $aliasedPropertyProvider);
        $queryBuilder = new QueryBuilder($comparisonFactory, $entityManager);
        return new EnhancedImplementation($queryBuilder);
    }
}
