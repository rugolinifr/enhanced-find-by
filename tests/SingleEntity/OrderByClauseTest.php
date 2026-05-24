<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Tests\SingleEntity;

use PHPUnit\Framework\Attributes\DataProvider;
use Rugolinifr\EnhancedFindBy\Tests\Entity\Owner;
use Rugolinifr\EnhancedFindBy\Tests\Shared\AbstractTestEntity;

class OrderByClauseTest extends AbstractTestEntity
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        (new SingleEntityFixture(static::$entityManager))->createOwners();
    }

    /**
     * @param array<string, mixed> $criteria
     * @param array<string, string> $orderBy
     * @param string[] $expectedNames
     */
    #[DataProvider('provideOrderByData')]
    public function testOrderBy(array $criteria, array $orderBy, array $expectedNames): void
    {
        $this->givenIHaveAnEnhancedFindBy();
        $this->whenIOrderByEntity($criteria, $orderBy, Owner::class);
        $this->thenIGetExpectedEntities($expectedNames, Owner::class);
    }

    public static function provideOrderByData(): array
    {
        return [
            'order by nothing, no criteria' => [
                'criteria' => [],
                'orderBy' => [],
                'expectedNames' => ['alice', 'bob', 'carl'],
            ],
            'order by string ascending' => [
                'criteria' => [],
                'orderBy' => ['name' => 'asc'],
                'expectedNames' => ['alice', 'bob', 'carl'],
            ],
            'order by string descending' => [
                'criteria' => [],
                'orderBy' => ['name' => 'desc'],
                'expectedNames' => ['carl', 'bob', 'alice'],
            ],
            'order by integer ascending' => [
                'criteria' => [],
                'orderBy' => ['count' => 'asc'],
                'expectedNames' => ['alice', 'bob', 'carl'],
            ],
            'order by integer descending' => [
                'criteria' => [],
                'orderBy' => ['count' => 'desc'],
                'expectedNames' => ['carl', 'bob', 'alice'],
            ],
            'order by float ascending' => [
                'criteria' => [],
                'orderBy' => ['score' => 'asc'],
                'expectedNames' => ['alice', 'bob', 'carl'],
            ],
            'order by float descending' => [
                'criteria' => [],
                'orderBy' => ['score' => 'desc'],
                'expectedNames' => ['carl', 'bob', 'alice'],
            ],
            'order by date ascending' => [
                'criteria' => [],
                'orderBy' => ['birth' => 'asc'],
                'expectedNames' => ['alice', 'carl', 'bob'],
            ],
            'order by date descending' => [
                'criteria' => [],
                'orderBy' => ['birth' => 'desc'],
                'expectedNames' => ['bob', 'carl', 'alice'],
            ],
            'order by boolean ascending' => [
                'criteria' => [],
                'orderBy' => ['isMale' => 'asc'],
                'expectedNames' => ['alice', 'bob', 'carl'],
            ],
            'order by boolean descending' => [
                'criteria' => [],
                'orderBy' => ['isMale' => 'desc'],
                'expectedNames' => ['bob', 'carl', 'alice'],
            ],
            'order by multiple properties' => [
                'criteria' => [],
                'orderBy' => [
                    'isMale' => 'desc',
                    'name' => 'desc',
                ],
                'expectedNames' => ['carl', 'bob', 'alice'],
            ],
            'order by with criteria' => [
                'criteria' => ['name like' => '%a%'],
                'orderBy' => ['id' => 'desc'],
                'expectedNames' => ['carl', 'alice'],
            ],
            //TODO: error test on property repeated twice or more
        ];
    }
}
