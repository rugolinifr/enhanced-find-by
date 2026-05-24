<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Tests\SingleEntity;

use DateTime;
use DateTimeImmutable;
use Rugolinifr\EnhancedFindBy\Tests\Shared\HandSkillEnum;

class LessThanOrEqualOperatorTest extends AbstractTestSingleEntity
{

    public static function provideSingleEntityCriteria(): array
    {
        return [
            'filter on less or equal string' => [
                'criteria' => ['name <=' => 'bob'],
                'expectedNames' => ['alice', 'bob'],
            ],
            'filter on less or equal integer' => [
                'criteria' => ['count <=' => 4],
                'expectedNames' => ['alice', 'bob'],
            ],
            'filter on less or equal integer not found' => [
                'criteria' => ['count <=' => 1],
                'expectedNames' => [],
            ],
            'filter on less or equal float' => [
                'criteria' => ['score <=' => 1.0],
                'expectedNames' => ['alice', 'bob'],
            ],
            'filter on less or equal float multiple results' => [
                'criteria' => ['score <=' => 1.5],
                'expectedNames' => ['alice', 'bob', 'carl'],
            ],
            'filter on less or equal date time immutable' => [
                'criteria' => ['birth <=' => new DateTimeImmutable('2000-01-01 00:00:00')],
                'expectedNames' => ['alice'],
            ],
            'filter on less or equal date time mutable' => [
                'criteria' => ['birth <=' => new DateTime('today')],
                'expectedNames' => ['alice', 'carl'],
            ],
            'filter on less or equal multiple properties' => [
                'criteria' => [
                    'count <=' => 8,
                    'score <=' => 1.0,
                ],
                'expectedNames' => ['alice', 'bob'],
            ],
            'filter on less than or equal enum' => [
                'criteria' => ['handSkill <=' => HandSkillEnum::RIGHT],
                'expectedNames' => ['alice', 'bob'],
            ],
        ];
    }
}