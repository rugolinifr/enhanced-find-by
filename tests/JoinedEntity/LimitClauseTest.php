<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Tests\JoinedEntity;

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

    public function testLimitWithToManyJoin(): void
    {
        $this->givenIHaveAnEnhancedFindBy();
        $this->whenILimitEntitiesThroughToManyInnerJoin();
        $this->thenIGetExpectedEntities(['fanny', 'georges'], Owner::class);
    }

    /**
     * fanny has multiple products, meaning the join returns multiple rows.
     * How does the LIMIT clause handle that ?
     */
    private function whenILimitEntitiesThroughToManyInnerJoin(): void
    {
        try {
            $where = [
                'stores.products.id >' => 0,
                'name =' => ['fanny', 'georges']
            ];
            $this->resultSet = static::$finder->findBy(Owner::class, $where, limit: 2, offset: 0);
        } catch (EnhancedFindByExceptionInterface $e) {
            $this->lastException = $e;
        }
    }
}
