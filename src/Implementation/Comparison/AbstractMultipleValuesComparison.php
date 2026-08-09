<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Implementation\Comparison;

use Rugolinifr\EnhancedFindBy\Implementation\Shared\AliasedPropertyProvider;
use Rugolinifr\EnhancedFindBy\Implementation\Shared\EnhancedImplementationInvalidArgumentException as ImplementationInvalidArgumentException;
use Rugolinifr\EnhancedFindBy\Implementation\Shared\Incrementor;
use Rugolinifr\EnhancedFindBy\Implementation\Shared\JoinClauseProvider;
use Rugolinifr\EnhancedFindBy\Implementation\Shared\StrictFunction as SF;

abstract class AbstractMultipleValuesComparison extends AbstractComparison
{
    public function __construct(
        string $propertyPath,
        array $splitPropertyPath,
        string $operator,
        mixed $value,
        JoinClauseProvider $joinClauseProvider,
        AliasedPropertyProvider $aliasedPropertyProvider,
        private string $jonctionOperator,
    ) {
        parent::__construct(
            $propertyPath,
            $splitPropertyPath,
            $operator,
            $value,
            $joinClauseProvider,
            $aliasedPropertyProvider,
        );
    }

    protected function handleMultipleValues(array $values, Incrementor $incrementor): string
    {
        $this->assertValuesNotEmpty($values);
        return $this->buildWhereClause($values, $incrementor);
    }

    /**
     * @param mixed[] $values
     *
     * @throws ImplementationInvalidArgumentException
     */
    private function assertValuesNotEmpty(array $values): void
    {
        if (empty($values)) {
            $msg = "Invalid value for the \"$this->propertyPath\" property.";
            throw new ImplementationInvalidArgumentException($msg);
        }
    }

    /**
     * @param mixed[] $values
     *
     * @throws ImplementationInvalidArgumentException
     */
    private function buildWhereClause(array $values, Incrementor $incrementor): string
    {
        $where = '( ';
        foreach ($values as $subValue) {
            if ($subValue === null) {
                $where .= $this->handleNullValue($incrementor);
            } else {
                $where .= $this->handleSingleValue($subValue, $incrementor);
            }
            $where .= " $this->jonctionOperator ";
        }
        return SF::preg_replace("/ $this->jonctionOperator $/", ' )', $where);
    }
}
