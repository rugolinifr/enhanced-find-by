<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Tests\JoinedEntity;

use DateTimeImmutable;
use Rugolinifr\EnhancedFindBy\Tests\Shared\HandSkillEnum;

class LessThanOrEqualOperatorTest extends AbstractTestJoinedEntity
{

    public static function provideJoinedEntityManyToOneCriteria(): array
    {
        return [
            'filter by less than or equal exact transitive string' => [
                'criteria' => ['store.name <=' => 'The Eric store'],
                'expectedNames' => ['elderberry'],
            ],
            'filter by less than or equal exact transitive integer' => [
                'criteria' => ['store.owner.count <=' => 14],
                'expectedNames' => ['elderberry'],
            ],
            'filter by less than or equal exact transitive float' => [
                'criteria' => ['store.owner.score <=' => 12.0],
                'expectedNames' => ['elderberry', 'fig', 'feijoa', 'filbert', 'farkleberry'],
            ],
            'filter by less than or equal exact transitive date' => [
                'criteria' => ['store.owner.birth <=' => new DateTimeImmutable('2005-02-02 12:00:00')],
                'expectedNames' => ['elderberry'],
            ],
            'filter by less than or equal than enum' => [
                'criteria' => ['store.owner.handSkill <=' => HandSkillEnum::RIGHT],
                'expectedNames' => ['elderberry'],
            ],
        ];
    }
}
