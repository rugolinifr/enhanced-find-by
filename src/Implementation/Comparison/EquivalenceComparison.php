<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Implementation\Comparison;

use BackedEnum;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use Rugolinifr\EnhancedFindBy\Implementation\Shared\AliasedPropertyProvider;
use Rugolinifr\EnhancedFindBy\Implementation\Shared\EnhancedImplementationInvalidArgumentException as ImplementationInvalidArgumentException;
use Rugolinifr\EnhancedFindBy\Implementation\Shared\Incrementor;
use Rugolinifr\EnhancedFindBy\Implementation\Shared\JoinClauseProvider;
use Throwable;

class EquivalenceComparison extends AbstractMultipleValuesComparison
{
    /**
     * @param string[] $splitPropertyPath
     */
    public function __construct(
        string $propertyPath,
        array $splitPropertyPath,
        string $operator,
        mixed $value,
        JoinClauseProvider $joinClauseProvider,
        AliasedPropertyProvider $aliasedPropertyProvider,
        string $jonctionOperator,
        protected string $nullOperator,
        protected string $comparisonOperator,
        private EntityManagerInterface $entityManager,
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
        $aliasedProperty = $this->getAliasedLastProperty($incrementor);
        return "$aliasedProperty $this->nullOperator NULL";
    }

    protected function handleSingleValue(mixed $value, Incrementor $incrementor): string
    {
        if ($this->isValidSingleValue($value)) {
            return $this->handleValidSingleValue($incrementor, $value);
        }
        throw new ImplementationInvalidArgumentException("Invalid value for the \"$this->propertyPath\" property.");
    }

    private function isValidSingleValue(mixed $value): bool
    {
        return is_string($value) ||
            is_int($value) ||
            is_float($value) ||
            is_bool($value) ||
            $value instanceof DateTimeInterface ||
            $value instanceof BackedEnum ||
            $this->isEntity($value)
        ;
    }

    private function isEntity(mixed $value): bool
    {
            return is_object($value) && $this->isDoctrineEntity($value);
    }

    private function isDoctrineEntity(object $object): bool
    {
        try {
            $this->entityManager->getClassMetadata($object::class);
            return true;
        } catch (Throwable) { //@phpstan-ignore catch.neverThrown
            return false;
        }
    }

    private function handleValidSingleValue(Incrementor $incrementor, mixed $value): string
    {
        $aliasedProperty = $this->getAliasedLastProperty($incrementor);
        $parameterName = $incrementor->addParameter($value);
        return "$aliasedProperty $this->comparisonOperator $parameterName";
    }
}
