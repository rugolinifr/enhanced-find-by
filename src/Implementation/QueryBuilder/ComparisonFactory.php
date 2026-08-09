<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Implementation\QueryBuilder;

use Doctrine\ORM\EntityManagerInterface;
use Rugolinifr\EnhancedFindBy\Implementation\Comparison\EquivalenceComparison;
use Rugolinifr\EnhancedFindBy\Implementation\Comparison\LikenessComparison;
use Rugolinifr\EnhancedFindBy\Implementation\Comparison\NullOrDifferentComparison;
use Rugolinifr\EnhancedFindBy\Implementation\Comparison\NullOrNotLikeComparison;
use Rugolinifr\EnhancedFindBy\Implementation\Comparison\SizeComparison;
use Rugolinifr\EnhancedFindBy\Implementation\OrderBy\OrderBy;
use Rugolinifr\EnhancedFindBy\Implementation\Shared\AliasedPropertyProvider;
use Rugolinifr\EnhancedFindBy\Implementation\Shared\ComparisonInterface;
use Rugolinifr\EnhancedFindBy\Implementation\Shared\EnhancedImplementationInvalidArgumentException as ImplementationInvalidArgumentException;
use Rugolinifr\EnhancedFindBy\Implementation\Shared\JoinClauseProvider;
use Rugolinifr\EnhancedFindBy\Implementation\Shared\StrictFunction as SF;

class ComparisonFactory
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private JoinClauseProvider $joinClauseProvider,
        private AliasedPropertyProvider $aliasedPropertyProvider,
    ) {
    }

    /**
     * @throws ImplementationInvalidArgumentException
     */
    public function createComparison(
        string $propertyPathAndOperator,
        mixed $value,
    ): ComparisonInterface {
        $cleanPropertyPathAndOperator = $this->cleanPropertyPathAndOperator($propertyPathAndOperator);
        $splitPropertyPath = explode('.', $cleanPropertyPathAndOperator);
        $size = count($splitPropertyPath);
        if ($size < 2) {
            throw new ImplementationInvalidArgumentException("The property path \"$propertyPathAndOperator\" is invalid.");
        }
        $splitPropertyPath = $this->cleanUpDoctrineEmbeddable($splitPropertyPath);
        $operator = $splitPropertyPath[$size - 1];
        $splitPropertyPath = array_slice($splitPropertyPath, 0, $size - 1);
        $comparison = $this->createAppropriateComparison(
            $cleanPropertyPathAndOperator,
            $splitPropertyPath,
            $operator,
            $value,
            $propertyPathAndOperator
        );
        return $comparison;
    }

    /**
     * @param string[] $splitPropertyPath
     *
     * @throws ImplementationInvalidArgumentException
     */
    private function createAppropriateComparison(
        string $cleanPropertyPathAndOperator,
        array $splitPropertyPath,
        string $operator,
        mixed $value,
        string $propertyPathAndOperator,
    ): ComparisonInterface {
        if ($operator === '=') {
            return $this->equivalence($cleanPropertyPathAndOperator, $splitPropertyPath, $operator, $value);
        }
        if ($operator === '!=') {
            return $this->difference($cleanPropertyPathAndOperator, $splitPropertyPath, $operator, $value);
        }
        if ($operator === '!==') {
            return $this->nullOrDifference($cleanPropertyPathAndOperator, $splitPropertyPath, $operator, $value);
        }
        if (in_array($operator, ['<', '<=', '>', '>='])) {
            return $this->size($cleanPropertyPathAndOperator, $splitPropertyPath, $operator, $value);
        }
        if ($operator === 'like') {
            return $this->likeness($cleanPropertyPathAndOperator, $splitPropertyPath, $operator, $value);
        }
        if ($operator === 'not_like') {
            return $this->unlikeness($cleanPropertyPathAndOperator, $splitPropertyPath, $operator, $value);
        }
        if ($operator === 'n_not_like') {
            return $this->nullOrUnlikeness($cleanPropertyPathAndOperator, $splitPropertyPath, $operator, $value);
        }
        throw new ImplementationInvalidArgumentException("The property path \"$propertyPathAndOperator\" has invalid operator.");
    }

    /**
     * @param string[] $splitPropertyPath
     * @return string[]
     */
    private function cleanUpDoctrineEmbeddable(array $splitPropertyPath): array
    {
        $size = count($splitPropertyPath);
        for ($i = 0; $i < $size; $i++) {
            $splitPropertyPath[$i] = str_replace('->', '.', $splitPropertyPath[$i]);
        }
        return $splitPropertyPath;
    }

    /**
     * @param string[] $splitPropertyPath
     */
    private function equivalence(
        string $cleanPropertyPathAndOperator,
        array $splitPropertyPath,
        string $operator,
        mixed $value
    ): EquivalenceComparison {
        return new EquivalenceComparison(
            $cleanPropertyPathAndOperator,
            $splitPropertyPath,
            $operator,
            $value,
            $this->joinClauseProvider,
            $this->aliasedPropertyProvider,
            'OR',
            'IS',
            '=',
            $this->entityManager,
        );
    }

    /**
     * @param string[] $splitPropertyPath
     */
    private function difference(
        string $cleanPropertyPathAndOperator,
        array $splitPropertyPath,
        string $operator,
        mixed $value
    ): EquivalenceComparison {
        return new EquivalenceComparison(
            $cleanPropertyPathAndOperator,
            $splitPropertyPath,
            $operator,
            $value,
            $this->joinClauseProvider,
            $this->aliasedPropertyProvider,
            'AND',
            'IS NOT',
            '!=',
            $this->entityManager,
        );
    }

    /**
     * @param string[] $splitPropertyPath
     */
    private function nullOrDifference(
        string $cleanPropertyPathAndOperator,
        array $splitPropertyPath,
        string $operator,
        mixed $value
    ): EquivalenceComparison {
        return new NullOrDifferentComparison(
            $cleanPropertyPathAndOperator,
            $splitPropertyPath,
            $operator,
            $value,
            $this->joinClauseProvider,
            $this->aliasedPropertyProvider,
            'AND',
            'IS NOT',
            '!=',
            $this->entityManager,
        );
    }

    /**
     * @param string[] $splitPropertyPath
     */
    private function size(
        string $cleanPropertyPathAndOperator,
        array $splitPropertyPath,
        string $operator,
        mixed $value
    ): SizeComparison {
        return new SizeComparison(
            $cleanPropertyPathAndOperator,
            $splitPropertyPath,
            $operator,
            $value,
            $this->joinClauseProvider,
            $this->aliasedPropertyProvider,
        );
    }

    /**
     * @param string[] $splitPropertyPath
     */
    private function likeness(
        string $cleanPropertyPathAndOperator,
        array $splitPropertyPath,
        string $operator,
        mixed $value
    ): LikenessComparison {
        return new LikenessComparison(
            $cleanPropertyPathAndOperator,
            $splitPropertyPath,
            $operator,
            $value,
            $this->joinClauseProvider,
            $this->aliasedPropertyProvider,
            'OR',
            'LIKE',
        );
    }

    /**
     * @param string[] $splitPropertyPath
     */
    private function unlikeness(
        string $cleanPropertyPathAndOperator,
        array $splitPropertyPath,
        string $operator,
        mixed $value
    ): LikenessComparison {
        return new LikenessComparison(
            $cleanPropertyPathAndOperator,
            $splitPropertyPath,
            $operator,
            $value,
            $this->joinClauseProvider,
            $this->aliasedPropertyProvider,
            'AND',
            'NOT LIKE'
        );
    }

    /**
     * @param string[] $splitPropertyPath
     */
    private function nullOrUnlikeness(
        string $cleanPropertyPathAndOperator,
        array $splitPropertyPath,
        string $operator,
        mixed $value
    ): NullOrNotLikeComparison {
        $unlike = $this->unlikeness($cleanPropertyPathAndOperator, $splitPropertyPath, $operator, $value);
        return new NullOrNotLikeComparison($unlike, $this->aliasedPropertyProvider, $splitPropertyPath);
    }

    /**
     * @throws ImplementationInvalidArgumentException
     */
    public function createOrderBy(string $propertyPath, string $sortStrategy): OrderBy
    {
        $cleanPropertyPath = $this->cleanPropertyPathAndOperator($propertyPath);
        $splitPropertyPath = explode('.', $cleanPropertyPath);
        $splitPropertyPath = $this->cleanUpDoctrineEmbeddable($splitPropertyPath);
        $cleanSortStrategy = $this->cleanSortStrategy($sortStrategy);
        return new OrderBy(
            $cleanPropertyPath,
            $splitPropertyPath,
            $cleanSortStrategy,
            $this->joinClauseProvider,
            $this->aliasedPropertyProvider,
        );
    }

    private function cleanPropertyPathAndOperator(string $propertyPathAndOperator): string
    {
        $cleaned = trim($propertyPathAndOperator);
        $cleaned = SF::preg_replace('/ {2,}/', ' ', $cleaned);
        return str_replace(' ', '.', $cleaned);
    }

    /**
     * @throws ImplementationInvalidArgumentException
     */
    private function cleanSortStrategy(string $sortStrategy): string
    {
        if (in_array($sortStrategy, ['asc', 'ASC'])) {
            return 'ASC';
        }
        if (in_array($sortStrategy, ['desc', 'DESC'])) {
            return 'DESC';
        }
        throw new ImplementationInvalidArgumentException("Invalid sort strategy value \"$sortStrategy\".");
    }
}
