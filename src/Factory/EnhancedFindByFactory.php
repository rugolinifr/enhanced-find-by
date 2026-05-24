<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Rugolinifr\EnhancedFindBy\Contract\EnhancedFindByInterface;
use Rugolinifr\EnhancedFindBy\Implementation\Finder\ComparisonFactory;
use Rugolinifr\EnhancedFindBy\Implementation\Finder\EnhancedFindBy;
use Rugolinifr\EnhancedFindBy\Implementation\Shared\AliasedPropertyProvider;
use Rugolinifr\EnhancedFindBy\Implementation\Shared\JoinClauseProvider;

class EnhancedFindByFactory
{
    public function createEnhancedFindBy(EntityManagerInterface $entityManager): EnhancedFindByInterface
    {
        $joinClauseProvider = new JoinClauseProvider();
        $aliasedPropertyProvider = new AliasedPropertyProvider();
        $comparisonFactory = new ComparisonFactory($entityManager, $joinClauseProvider, $aliasedPropertyProvider);
        return new EnhancedFindBy($entityManager, $comparisonFactory);
    }
}
