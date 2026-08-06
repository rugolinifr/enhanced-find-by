<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Tests\Count;

use PHPUnit\Framework\Attributes\DataProvider;
use Rugolinifr\EnhancedFindBy\Tests\Entity\Owner;
use Rugolinifr\EnhancedFindBy\Tests\Entity\Product;
use Rugolinifr\EnhancedFindBy\Tests\Entity\Store;
use Rugolinifr\EnhancedFindBy\Tests\Shared\AbstractTestEntity;

class CountTest extends AbstractTestEntity
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        (new CountFixture(self::$entityManager))->createFixtures();
    }

    /**
     * @template T
     *
     * @param class-string<T> $classname
     * @param array<string, mixed> $where
     */
    #[DataProvider('provideDataForSingleEntityCount')]
    public function testEnhancedCountOnSingleEntity(
        string $classname,
        array $where,
        int $expectedCount,
    ): void {
        $this->givenIHaveAnEnhancedCount();
        $this->whenICountEntities($classname, $where);
        $this->thenIGetExpectedCount($expectedCount);
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public static function provideDataForSingleEntityCount(): array
    {
        return [
            'count every owner' => [
                'classname' => Owner::class,
                'where' => [],
                'expectedCount' => 4,
            ],
            'count every store' => [
                'classname' => Store::class,
                'where' => [],
                'expectedCount' => 3,
            ],
            'count every product' => [
                'classname' => Product::class,
                'where' => [],
                'expectedCount' => 5,
            ],
            'where clause on current entity' => [
                'classname' => Owner::class,
                'where' => ['name =' => 'warren'],
                'expectedCount' => 1,
            ],
            'where clause on joined entity (*toOne)' => [
                'classname' => Product::class,
                'where' => ['store.owner.name =' => ['warren', 'xavier', 'yann']],
                'expectedCount' => 2,
            ],
            'where clause on joined entity (*toMany) 1/2' => [
                'classname' => Owner::class,
                'where' => ['stores.products.name like' => "%'s%"],
                'expectedCount' => 2,
            ],
            'where clause on joined entity (*toMany) 2/2' => [
                'classname' => Owner::class,
                'where' => ['stores.products.name like' => "%zoe%"],
                'expectedCount' => 1,
            ],
        ];
    }

    private function thenIGetExpectedCount(int $expectedCount): void
    {
        self::assertNull(
            $this->lastException,
            "The enhanced counter throw an unexpected exception: {$this->lastException?->getMessage()}.",
        );
        $this->assertSame(
            $expectedCount,
            $this->countResult,
            'The enhanced counter did not return the expected count.',
        );
    }
}
