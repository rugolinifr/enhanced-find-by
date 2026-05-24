<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Tests\JoinedEntity;

use DateTimeImmutable;
use Rugolinifr\EnhancedFindBy\Tests\Shared\HandSkillEnum;

class LessThanOperatorTest extends AbstractTestJoinedEntity
{

    public static function provideJoinedEntityManyToOneCriteria(): array
    {
        return [
            'filter by less than transitive string' => [
                'criteria' => ['store.name <' => 'The Fanny first store'],
                'expectedNames' => ['elderberry'],
            ],
            'filter by less than transitive integer' => [
                'criteria' => ['store.owner.count <' => 15],
                'expectedNames' => ['elderberry'],
            ],
            'filter by less than or equal transitive integer' => [
                'criteria' => ['store.owner.count <' => 14],
                'expectedNames' => [],
            ],
            'filter by less than or equal transitive float' => [
                'criteria' => ['store.owner.score <' => 12.1],
                'expectedNames' => ['elderberry', 'fig', 'feijoa', 'filbert', 'farkleberry'],
            ],
            'filter by less than or equal transitive date' => [
                'criteria' => ['store.owner.birth <' => new DateTimeImmutable('2005-02-02 13:00:00')],
                'expectedNames' => ['elderberry'],
            ],
            'filter by less than enum' => [
                'criteria' => ['store.owner.handSkill <' => HandSkillEnum::RIGHT],
                'expectedNames' => [],
            ],
        ];
    }
}
