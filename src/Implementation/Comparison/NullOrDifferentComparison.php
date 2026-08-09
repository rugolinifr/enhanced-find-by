<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Implementation\Comparison;

use Rugolinifr\EnhancedFindBy\Implementation\Shared\EnhancedImplementationInvalidArgumentException as EIInvalidArgumentException;
use Rugolinifr\EnhancedFindBy\Implementation\Shared\Incrementor;

class NullOrDifferentComparison extends EquivalenceComparison
{
    public function getWhereDql(Incrementor $incrementor): string
    {
        $aliasedProperty = $this->getAliasedLastProperty($incrementor);
        $dql = parent::getWhereDql($incrementor);
        return "( $aliasedProperty IS NULL OR ( $dql ) )";
    }

    protected function handleNullValue(Incrementor $incrementor): string
    {
        $msg = "Invalid value for the property \"$this->propertyPath\": NULL is forbidden.";
        throw new EIInvalidArgumentException($msg);
    }
}
