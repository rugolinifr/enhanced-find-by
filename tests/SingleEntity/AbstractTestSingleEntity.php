<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Tests\SingleEntity;

use PHPUnit\Framework\Attributes\DataProvider;
use Rugolinifr\EnhancedFindBy\Tests\Entity\Owner;
use Rugolinifr\EnhancedFindBy\Tests\Shared\AbstractTestEntity;

abstract class AbstractTestSingleEntity extends AbstractTestEntity
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        (new SingleEntityFixture(static::$entityManager))->createOwners();
    }

    /**
     * @param array<string, mixed> $criteria
     * @param string[] $expectedNames
     */
    #[DataProvider('provideSingleEntityCriteria')]
    public function testSingleEntityCriteria(
        array $criteria,
        array $expectedNames,
    ): void {
        $this->givenIHaveAnEnhancedFindBy();
        $this->whenISearchEntityByOperator($criteria, Owner::class);
        $this->thenIGetExpectedEntities($expectedNames, 'owner');
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    abstract public static function provideSingleEntityCriteria(): array;
}