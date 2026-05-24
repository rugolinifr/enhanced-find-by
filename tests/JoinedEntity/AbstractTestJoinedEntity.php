<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Tests\JoinedEntity;

use PHPUnit\Framework\Attributes\DataProvider;
use Rugolinifr\EnhancedFindBy\Tests\Entity\Product;
use Rugolinifr\EnhancedFindBy\Tests\Shared\AbstractTestEntity;

abstract class AbstractTestJoinedEntity extends AbstractTestEntity
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        (new JoinedEntityFixture(static::$entityManager))->createEntities();
    }

    /**
     * @param array<string, mixed> $criteria
     * @param string[] $expectedNames
     */
    #[DataProvider('provideJoinedEntityManyToOneCriteria')]
    public function testJoinedEntityManyToOneCriteria(
        array $criteria,
        array $expectedNames,
    ): void {
        $this->givenIHaveAnEnhancedFindBy();
        $this->whenISearchEntityByOperator($criteria, Product::class);
        $this->thenIGetExpectedEntities($expectedNames, 'product');
    }

    abstract public static function provideJoinedEntityManyToOneCriteria(): array;
}