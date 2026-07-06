<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Tests\JoinedEntity;

use PHPUnit\Framework\Attributes\DataProvider;
use Rugolinifr\EnhancedFindBy\Tests\Entity\Product;
use Rugolinifr\EnhancedFindBy\Tests\Shared\AbstractTestEntity;

class OrderByClauseTest extends AbstractTestEntity
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        (new JoinedEntityFixture(static::$entityManager))->createEntities();
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
        $this->whenIOrderByEntity($criteria, $orderBy, Product::class);
        $this->thenIGetExpectedEntities($expectedNames, Product::class);
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public static function provideOrderByData(): array
    {
        $ericFirst = ['elderberry', 'fig', 'feijoa', 'filbert', 'farkleberry'];
        $fannyFirst = ['fig', 'feijoa', 'filbert', 'farkleberry', 'elderberry'];

        return [
            'order by string ascending' => [
                'criteria' => [],
                'orderBy' => ['store.owner.name' => 'asc'],
                'expectedNames' => $ericFirst,
            ],
            'order by string descending' => [
                'criteria' => [],
                'orderBy' => ['store.owner.name' => 'desc'],
                'expectedNames' => $fannyFirst,
            ],
            'order by integer ascending' => [
                'criteria' => [],
                'orderBy' => ['store.owner.count' => 'asc'],
                'expectedNames' => $ericFirst,
            ],
            'order by integer descending' => [
                'criteria' => [],
                'orderBy' => ['store.owner.count' => 'desc'],
                'expectedNames' => $fannyFirst,
            ],
            'order by float ascending' => [
                'criteria' => [],
                'orderBy' => ['store.owner.score' => 'asc'],
                'expectedNames' => $ericFirst,
            ],
            'order by float descending' => [
                'criteria' => [],
                'orderBy' => ['store.owner.score' => 'desc'],
                'expectedNames' => $fannyFirst,
            ],
            'order by date ascending' => [
                'criteria' => [],
                'orderBy' => ['store.owner.birth' => 'asc'],
                'expectedNames' => $ericFirst,
            ],
            'order by date descending' => [
                'criteria' => [],
                'orderBy' => ['store.owner.birth' => 'desc'],
                'expectedNames' => $fannyFirst,
            ],
            'order by boolean ascending' => [
                'criteria' => [],
                'orderBy' => ['store.owner.isMale' => 'asc'],
                'expectedNames' => $fannyFirst,
            ],
            'order by boolean descending' => [
                'criteria' => [],
                'orderBy' => ['store.owner.isMale' => 'desc'],
                'expectedNames' => $ericFirst,
            ],
            'order by multiple properties (store.owner)' => [
                'criteria' => [],
                'orderBy' => [
                    'store.owner.isMale' => 'desc',
                    'store.owner.name' => 'desc',
                ],
                'expectedNames' => $ericFirst,
            ],
            'order by with criteria, but order by forces a new join' => [
                'criteria' => ['store.name like' => 'The%'], // 'The Eric store', 'The Fanny store'
                'orderBy' => ['store.owner.name' => 'desc'],
                'expectedNames' => $fannyFirst,
            ],
            'order by with criteria, order by does not force a join' => [
                'criteria' => ['store.owner.score <' => 99],
                'orderBy' => ['store.name' => 'asc'],
                'expectedNames' => $ericFirst,
            ],
            'order by embeddable transitive string ascending' => [
                'criteria' => [],
                'orderBy' => ['store.owner.address->streetName' => 'asc'],
                'expectedNames' => $ericFirst,
            ],
            'order by embeddable transitive string descending' => [
                'criteria' => [],
                'orderBy' => ['store.owner.address->streetName' => 'desc'],
                'expectedNames' => $fannyFirst,
            ],
            'order by embeddable transitive integer ascending' => [
                'criteria' => [],
                'orderBy' => ['store.owner.address->number' => 'asc'],
                'expectedNames' => $ericFirst,
            ],
            'order by embeddable transitive integer descending' => [
                'criteria' => [],
                'orderBy' => ['store.owner.address->number' => 'desc'],
                'expectedNames' => $fannyFirst,
            ],
            'order by embeddable transitive float ascending' => [
                'criteria' => [],
                'orderBy' => ['store.owner.address->valueOverAveragePrice' => 'asc'],
                'expectedNames' => $ericFirst,
            ],
            'order by embeddable transitive float descending' => [
                'criteria' => [],
                'orderBy' => ['store.owner.address->valueOverAveragePrice' => 'desc'],
                'expectedNames' => $fannyFirst,
            ],
            'order by embeddable transitive date ascending' => [
                'criteria' => [],
                'orderBy' => ['store.owner.address->creationDate' => 'asc'],
                'expectedNames' => $fannyFirst,
            ],
            'order by embeddable transitive date descending' => [
                'criteria' => [],
                'orderBy' => ['store.owner.address->creationDate' => 'desc'],
                'expectedNames' => $ericFirst,
            ],
            'order by embeddable transitive enum ascending' => [
                'criteria' => [],
                'orderBy' => ['store.owner.address->placeKind' => 'asc'],
                'expectedNames' => $ericFirst,
            ],
            'order by embeddable transitive enum descending' => [
                'criteria' => [],
                'orderBy' => ['store.owner.address->placeKind' => 'desc'],
                'expectedNames' => $fannyFirst,
            ],
        ];
    }
}
