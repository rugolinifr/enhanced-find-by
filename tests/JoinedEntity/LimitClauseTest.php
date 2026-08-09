<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Tests\JoinedEntity;

use PHPUnit\Framework\Attributes\DataProvider;
use Rugolinifr\EnhancedFindBy\Contract\EnhancedFindByExceptionInterface;
use Rugolinifr\EnhancedFindBy\Tests\Entity\Owner;
use Rugolinifr\EnhancedFindBy\Tests\Shared\AbstractTestEntity;

class LimitClauseTest extends AbstractTestEntity
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        $joinedEntityFixture = new JoinedEntityFixture(static::$entityManager);
        $joinedEntityFixture->createEntities();
        $joinedEntityFixture->createOwnerForLimitTest();
    }


    /**
     * @param array<string, mixed> $where
     * @param array<string, string> $orderBy
     * @param string[] $expectedNames
     */
    #[DataProvider('provideDataForLimitWithOrder')]
    public function testLimitWithToManyJoin(
        array $where,
        array $orderBy,
        array $expectedNames,
    ): void {
        $this->givenIHaveAnEnhancedFindBy();
        $this->whenILimitEntitiesThroughToManyInnerJoin($where, $orderBy);
        $this->thenIGetExpectedEntities($expectedNames, Owner::class);
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public static function provideDataForLimitWithOrder(): array
    {
        return [
            'just WHERE clause joins' => [
                'where' => ['stores.products.id >' => 0],
                'orderBy' => [],
                'expectedNames' => ['eric', 'fanny', 'georges'],
            ],
            'just ORDER BY ASC clause joins' => [
                'where' => [],
                'orderBy' => ['stores.products.id' => 'ASC'],
                'expectedNames' => ['eric', 'fanny', 'georges'],
            ],
            'just ORDER BY DESC clause joins' => [
                'where' => [],
                'orderBy' => ['stores.products.id' => 'DESC'],
                'expectedNames' => ['georges', 'fanny', 'eric'],
            ],
            'WHERE and ORDER BY clauses current entity ASC' => [
                'where' => ['stores.products.id >' => 0],
                'orderBy' => ['id' => 'ASC'],
                'expectedNames' => ['eric', 'fanny', 'georges'],
            ],
            'WHERE and ORDER BY clauses current entity DESC' => [
                'where' => ['stores.products.id >' => 0],
                'orderBy' => ['id' => 'DESC'],
                'expectedNames' => ['georges', 'fanny', 'eric'],
            ],
            'WHERE and ORDER BY clauses joined entity ASC' => [
                'where' => ['stores.products.id >' => 0],
                'orderBy' => ['stores.products.id' => 'ASC'],
                'expectedNames' => ['eric', 'fanny', 'georges'],
            ],
            'WHERE and ORDER BY clauses joined entity DESC' => [
                'where' => ['stores.products.id >' => 0],
                'orderBy' => ['stores.products.id' => 'DESC'],
                'expectedNames' => ['georges', 'fanny', 'eric'],
            ],
        ];
    }

    /**
     * @param array<string, mixed> $where
     * @param array<string, string> $orderBy
     */
    private function whenILimitEntitiesThroughToManyInnerJoin(
        array $where,
        array $orderBy = [],
    ): void {
        try {
            $this->resultSet = static::$finder->findBy(Owner::class, $where, $orderBy, limit: 3, offset: 0);
        } catch (EnhancedFindByExceptionInterface $e) {
            $this->lastException = $e;
        }
    }
}
