<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Implementation\Comparison;

use Rugolinifr\EnhancedFindBy\Implementation\Shared\AliasedPropertyProvider;
use Rugolinifr\EnhancedFindBy\Implementation\Shared\ComparisonInterface;
use Rugolinifr\EnhancedFindBy\Implementation\Shared\EnhancedImplementationInvalidArgumentException as ImplementationInvalidArgumentException;
use Rugolinifr\EnhancedFindBy\Implementation\Shared\Incrementor;
use Rugolinifr\EnhancedFindBy\Implementation\Shared\JoinClauseProvider;

abstract class AbstractComparison implements ComparisonInterface
{
    /**
     * @param string[] $splitPropertyPath
     */
    public function __construct(
        public string $propertyPath,
        public array $splitPropertyPath,
        public string $operator,
        public mixed $value,
        private readonly JoinClauseProvider $joinClauseProvider,
        private readonly AliasedPropertyProvider $aliasedPropertyProvider,
    ) {
    }

    /**
     * @return string for example `e0.store` or `e2.description`.
     */
    protected function getAliasedLastProperty(Incrementor $incrementor): string
    {
        return $this->aliasedPropertyProvider->getAliasedProperty($this->splitPropertyPath, $incrementor);
    }

    public function getWhereDql(Incrementor $incrementor): string
    {
        if (is_array($this->value)) {
            return $this->handleMultipleValues($this->value, $incrementor);
        }
        if ($this->value === null) {
            return $this->handleNullValue($incrementor);
        }
        return $this->handleSingleValue($this->value, $incrementor);
    }

    /**
     * Returns the Doctrine Query Language `WHERE` clause of this comparison when its compared value is an array.
     *
     * @param mixed[] $values
     *
     * @throws ImplementationInvalidArgumentException
     */
    abstract protected function handleMultipleValues(array $values, Incrementor $incrementor): string;

    /**
     * Returns the Doctrine Query Language `WHERE` clause of this comparison when its compared value is `NULL`.
     *
     * @throws ImplementationInvalidArgumentException
     */
    abstract protected function handleNullValue(Incrementor $incrementor): string;

    /**
     * Returns the Doctrine Query Language `WHERE` clause of this comparison when its compared value is not an array.
     *
     * @throws ImplementationInvalidArgumentException
     */
    abstract protected function handleSingleValue(mixed $value, Incrementor $incrementor): string;

    /**
     * @inheritDoc
     */
    public function getJoinsDql(Incrementor $incrementor): array
    {
        return $this->joinClauseProvider->getJoinsDql($this->splitPropertyPath, $incrementor);
    }
}
