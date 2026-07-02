<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Tests\JoinedEntity;

class NotLikeOperatorTest extends AbstractTestJoinedEntity
{
    public static function provideJoinedEntityManyToOneCriteria(): array
    {
        return [
            'filter by not contain on single transitive string' => [
                'criteria' => ['store.owner.name not_like' => '%a%'],
                'expectedNames' => ['elderberry'],
            ],
            'filter by not contain on multiple transitive string' => [
                'criteria' => ['store.owner.name not_like' => ['%n%', '%r%']],
                'expectedNames' => [],
            ],
            'filter by not end on single transitive string' => [
                'criteria' => ['store.owner.name not_like' => '%y'],
                'expectedNames' => ['elderberry'],
            ],
            'filter by not end on multiple transitive string' => [
                'criteria' => ['store.owner.name not_like' => ['%c', '%y']],
                'expectedNames' => [],
            ],
            'filter by not start on single transitive string' => [
                'criteria' => ['store.owner.name not_like' => 'e%'],
                'expectedNames' => ['fig', 'feijoa', 'filbert', 'farkleberry'],
            ],
            'filter by not start on multiple transitive string' => [
                'criteria' => ['store.owner.name not_like' => ['e%', 'f%']],
                'expectedNames' => [],
            ],
            'filter on not like embeddable transitive string 1/2' => [
                'criteria' => ['store.owner.address->streetName not_like' => 'Eiffel%'],
                'expectedNames' => ['fig', 'feijoa', 'filbert', 'farkleberry'],
            ],
            'filter on not like embeddable transitive string 2/2' => [
                'criteria' => ['store.owner.address->streetName not_like' => '%avenue'],
                'expectedNames' => [],
            ],
        ];
    }
}
