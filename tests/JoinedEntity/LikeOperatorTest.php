<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Tests\JoinedEntity;

class LikeOperatorTest extends AbstractTestJoinedEntity
{

    public static function provideJoinedEntityManyToOneCriteria(): array
    {
        return [
            'filter by contains transitive string 1/2' => [
                'criteria' => ['store.owner.description like' => '%many%'],
                'expectedNames' => ['fig', 'feijoa', 'filbert', 'farkleberry'],
            ],
            'filter by contains transitive string 2/2' => [
                'criteria' => ['store.owner.description like' => '%store%'],
                'expectedNames' => ['elderberry', 'fig', 'feijoa', 'filbert', 'farkleberry'],
            ],
            'filter by ends with transitive string 1/2' => [
                'criteria' => ['store.owner.description like' => '%store'],
                'expectedNames' => ['elderberry'],
            ],
            'filter by ends with transitive string 2/2' => [
                'criteria' => ['store.owner.description like' => '%stores'],
                'expectedNames' => ['fig', 'feijoa', 'filbert', 'farkleberry'],
            ],
            'filter by starts with transitive string 1/2' => [
                'criteria' => ['store.owner.description like' => 'Eric%'],
                'expectedNames' => ['elderberry'],
            ],
            'filter by starts with transitive string 2/2' => [
                'criteria' => ['store.owner.description like' => 'Fanny%'],
                'expectedNames' => ['fig', 'feijoa', 'filbert', 'farkleberry'],
            ],
        ];
    }
}
