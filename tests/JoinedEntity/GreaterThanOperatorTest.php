<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Tests\JoinedEntity;

use DateTimeImmutable;
use Rugolinifr\EnhancedFindBy\Tests\Shared\HandSkillEnum;

class GreaterThanOperatorTest extends AbstractTestJoinedEntity
{

    public static function provideJoinedEntityManyToOneCriteria(): array
    {
        return [
            'filter by greater than transitive string' => [
                'criteria' => ['store.name >' => 'The Eric store'],
                'expectedNames' => ['fig', 'feijoa', 'filbert', 'farkleberry'],
            ],
            'filter by greater than transitive integer' => [
                'criteria' => ['store.owner.count >' => 14],
                'expectedNames' => ['fig', 'feijoa', 'filbert', 'farkleberry'],
            ],
            'filter by greater than exact transitive float' => [
                'criteria' => ['store.owner.score >' => 11.5],
                'expectedNames' => ['fig', 'feijoa', 'filbert', 'farkleberry'],
            ],
            'filter by greater than transitive date' => [
                'criteria' => ['store.owner.birth >' => new DateTimeImmutable('2005-02-02 12:00:00')],
                'expectedNames' => ['fig', 'feijoa', 'filbert', 'farkleberry'],
            ],
            'filter by greater than enum' => [
                'criteria' => ['store.owner.handSkill >' => HandSkillEnum::RIGHT],
                'expectedNames' => ['fig', 'feijoa', 'filbert', 'farkleberry'],
            ],
        ];
    }
}
