<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Implementation\OrderBy;

use Rugolinifr\EnhancedFindBy\Implementation\Shared\AliasedPropertyProvider;
use Rugolinifr\EnhancedFindBy\Implementation\Shared\Incrementor;
use Rugolinifr\EnhancedFindBy\Implementation\Shared\JoinClauseProvider;

class OrderBy
{
    /**
     * @param string[] $splitPropertyPath
     */
    public function __construct(
        public string $propertyPath,
        public array $splitPropertyPath,
        public string $sortStrategy,
        private JoinClauseProvider $joinClauseProvider,
        private AliasedPropertyProvider $aliasedPropertyProvider,
    ) {
    }

    /**
     * @return string[]
     */
    public function getJoinsDql(Incrementor $incrementor): array
    {
        return $this->joinClauseProvider->getJoinsDql($this->splitPropertyPath, $incrementor);
    }

    public function getOrderByDql(Incrementor $incrementor): string
    {
        $aliasedProperty = $this->aliasedPropertyProvider->getAliasedProperty($this->splitPropertyPath, $incrementor);
        return "$aliasedProperty $this->sortStrategy";
    }
}
