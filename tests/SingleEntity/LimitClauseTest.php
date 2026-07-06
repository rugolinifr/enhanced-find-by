<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Tests\SingleEntity;

use PHPUnit\Framework\Attributes\DataProvider;
use Rugolinifr\EnhancedFindBy\Tests\Entity\Owner;
use Rugolinifr\EnhancedFindBy\Tests\Shared\AbstractTestEntity;

class LimitClauseTest extends AbstractTestEntity
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        (new SingleEntityFixture(static::$entityManager))->createOwners();
    }

    /**
     * @param array<string, string> $orderBy
     * @param string[] $expectedNames
     */
    #[DataProvider('provideLimitClauses')]
    public function testLimitClause(
        array $orderBy,
        ?int $limit,
        int $offset,
        array $expectedNames,
    ): void {
        $this->givenIHaveAnEnhancedFindBy();
        $this->whenISearchEntityWithLimit($orderBy, $limit, $offset);
        $this->thenIGetExpectedEntities($expectedNames, Owner::class);
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public static function provideLimitClauses(): array
    {
        return [
            'limit is the entity collection size, no offset' => [
                'orderBy' => [],
                'limit' => 3,
                'offset' => 0,
                'expectedNames' => ['alice', 'bob', 'carl'],
            ],
            'limit is bigger than the entity collection size, no offset' => [
                'orderBy' => [],
                'limit' => 4,
                'offset' => 0,
                'expectedNames' => ['alice', 'bob', 'carl'],
            ],
            'limit is the shortest possible, no offset' => [
                'orderBy' => [],
                'limit' => 1,
                'offset' => 0,
                'expectedNames' => ['alice'],
            ],
            'limit is the entity collection size, offset middle' => [
                'orderBy' => [],
                'limit' => 3,
                'offset' => 1,
                'expectedNames' => ['bob', 'carl'],
            ],
            'limit is bigger than the entity collection size, offset last' => [
                'orderBy' => [],
                'limit' => 4,
                'offset' => 2,
                'expectedNames' => ['carl'],
            ],
            'limit is the shortest possible, offset last' => [
                'orderBy' => [],
                'limit' => 1,
                'offset' => 2,
                'expectedNames' => ['carl'],
            ],
            'offset is bigger than the entity collection size' => [
                'orderBy' => [],
                'limit' => 1,
                'offset' => 3,
                'expectedNames' => [],
            ],
            'order by is set before limit' => [
                'orderBy' => ['name' => 'DESC'],
                'limit' => 2,
                'offset' => 0,
                'expectedNames' => ['carl', 'bob'],
            ],
            //TODO: limit is negative
            //TODO: offset is negative
        ];
    }
}
