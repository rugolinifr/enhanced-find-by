<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Implementation\Comparison;

use Rugolinifr\EnhancedFindBy\Implementation\Shared\AliasedPropertyProvider;
use Rugolinifr\EnhancedFindBy\Implementation\Shared\EnhancedImplementationInvalidArgumentException as ImplementationInvalidArgumentException;
use Rugolinifr\EnhancedFindBy\Implementation\Shared\Incrementor;
use Rugolinifr\EnhancedFindBy\Implementation\Shared\JoinClauseProvider;

class LikenessComparison extends AbstractMultipleValuesComparison
{
    public function __construct(
        string $propertyPath,
        array $splitPropertyPath,
        string $operator,
        mixed $value,
        JoinClauseProvider $joinClauseProvider,
        AliasedPropertyProvider $aliasedPropertyProvider,
        string $jonctionOperator,
        private string $likeOperator,
    ) {
        parent::__construct(
            $propertyPath,
            $splitPropertyPath,
            $operator,
            $value,
            $joinClauseProvider,
            $aliasedPropertyProvider,
            $jonctionOperator,
        );
    }

    protected function handleNullValue(Incrementor $incrementor): string
    {
        $msg = "Invalid value for the property \"$this->propertyPath\": NULL is forbidden.";
        throw new ImplementationInvalidArgumentException($msg);
    }

    protected function handleSingleValue(mixed $value, Incrementor $incrementor): string
    {
        if (is_string($value)) {
            return $this->handleStringValue($incrementor, $value);
        }
        throw new ImplementationInvalidArgumentException("Invalid value for the \"$this->propertyPath\" property.");
    }

    private function handleStringValue(Incrementor $incrementor, string $value): string
    {
        $aliasedProperty = $this->getAliasedLastProperty($incrementor);
        $parameterName = $incrementor->addParameter($value);
        return "$aliasedProperty $this->likeOperator $parameterName";
    }
}
