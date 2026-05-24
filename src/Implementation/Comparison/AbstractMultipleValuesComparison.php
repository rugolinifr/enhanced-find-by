<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Implementation\Comparison;

use Rugolinifr\EnhancedFindBy\Contract\EnhancedFindByInvalidArgumentException;
use Rugolinifr\EnhancedFindBy\Implementation\Shared\AliasedPropertyProvider;
use Rugolinifr\EnhancedFindBy\Implementation\Shared\Incrementor;
use Rugolinifr\EnhancedFindBy\Implementation\Shared\JoinClauseProvider;

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

    /**
     * @inheritDoc
     */
    protected function handleMultipleValues(array $values, Incrementor $incrementor): string
    {
        $this->assertValuesNotEmpty($values);
        return $this->buildWhereClause($values, $incrementor);
    }

    /**
     * @param mixed[] $values
     */
    private function assertValuesNotEmpty(array $values): void
    {
        if (empty($values)) {
            $msg = "Invalid value for the \"$this->propertyPath\" property.";
            throw new EnhancedFindByInvalidArgumentException($msg);
        }
    }

    /**
     * @param mixed[] $values
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
        return preg_replace("/ $this->jonctionOperator $/", ' )', $where);
    }
}
