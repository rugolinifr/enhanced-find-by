<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Implementation\Comparison;

use BackedEnum;
use DateTimeInterface;
use Rugolinifr\EnhancedFindBy\Implementation\Shared\EnhancedImplementationInvalidArgumentException as EIInvalidArgumentException;
use Rugolinifr\EnhancedFindBy\Implementation\Shared\Incrementor;

class SizeComparison extends AbstractComparison
{
    protected function handleMultipleValues(array $values, Incrementor $incrementor): string
    {
        $msg = "Invalid value for the property \"$this->propertyPath\": array is forbidden.";
        throw new EIInvalidArgumentException($msg);
    }

    protected function handleNullValue(Incrementor $incrementor): string
    {
        $msg = "Invalid value for the property \"$this->propertyPath\": NULL is forbidden.";
        throw new EIInvalidArgumentException($msg);
    }

    protected function handleSingleValue(mixed $value, Incrementor $incrementor): string
    {
        if ($this->isValidValue($value)) {
            return $this->handleValidSingleValue($incrementor, $value);
        }
        throw new EIInvalidArgumentException("Invalid value for the \"$this->propertyPath\" property.");
    }

    private function isValidValue(mixed $value): bool
    {
        return is_string($value) ||
            is_int($value) ||
            is_float($value) ||
            $value instanceof DateTimeInterface ||
            $value instanceof BackedEnum
        ;
    }

    private function handleValidSingleValue(Incrementor $incrementor, mixed $value): string
    {
        $aliasedProperty = $this->getAliasedLastProperty($incrementor);
        $parameterName = $incrementor->addParameter($value);
        return "$aliasedProperty $this->operator $parameterName";
    }
}
