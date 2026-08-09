<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Implementation\Comparison;

use Rugolinifr\EnhancedFindBy\Implementation\Shared\AliasedPropertyProvider;
use Rugolinifr\EnhancedFindBy\Implementation\Shared\ComparisonInterface;
use Rugolinifr\EnhancedFindBy\Implementation\Shared\Incrementor;

class NullOrNotLikeComparison implements ComparisonInterface
{
    /**
     * @param string[] $splitProperty
     */
    public function __construct(
        private LikenessComparison $notLike,
        private AliasedPropertyProvider $aliasedPropertyProvider,
        private array $splitProperty,
    ) {
    }

    public function getWhereDql(Incrementor $incrementor): string
    {
        $aliasedProperty = $this->aliasedPropertyProvider->getAliasedProperty($this->splitProperty, $incrementor);
        $dql = $this->notLike->getWhereDql($incrementor);
        return "( $aliasedProperty IS NULL OR ( $dql ) )";
    }

    public function getJoinsDql(Incrementor $incrementor): array
    {
        return $this->notLike->getJoinsDql($incrementor);
    }
}
