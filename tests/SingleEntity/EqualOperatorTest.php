<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Tests\SingleEntity;

use DateTime;
use DateTimeImmutable;
use Rugolinifr\EnhancedFindBy\Tests\Shared\HandSkillEnum;

class EqualOperatorTest extends AbstractTestSingleEntity
{

    public static function provideSingleEntityCriteria(): array
    {
        return [
            'no criteria returns every entity' => [
                'criteria' => [],
                'expectedNames' => ['alice', 'bob', 'carl'],
            ],
            'filter on same string' => [
                'criteria' => ['name =' => 'alice'],
                'expectedNames' => ['alice'],
            ],
            'filter on same integer' => [
                'criteria' => ['count =' => 4],
                'expectedNames' => ['bob'],
            ],
            'filter on same date time immutable' => [
                'criteria' => ['birth =' => new DateTimeImmutable('2000-01-01 00:00:00')],
                'expectedNames' => ['alice'],
            ],
            'filter on same date time mutable' => [
                'criteria' => ['birth =' => new DateTime('2000-01-01 00:00:00')],
                'expectedNames' => ['alice'],
            ],
            'filter on same float' => [
                'criteria' => ['score =' => 1.5],
                'expectedNames' => ['carl'],
            ],
            'filter on same bool 1/2' => [
                'criteria' => ['isMale =' => false],
                'expectedNames' => ['alice'],
            ],
            'filter on same bool 2/2' => [
                'criteria' => ['isMale =' => true],
                'expectedNames' => ['bob', 'carl'],
            ],
            'filter on null value' => [
                'criteria' => ['description =' => null],
                'expectedNames' => ['alice'],
            ],
            'filter on multiple same string' => [
                'criteria' => ['name =' => ['alice', 'bob']],
                'expectedNames' => ['alice', 'bob'],
            ],
            'filter on multiple same integer' => [
                'criteria' => ['count =' => [4, 8]],
                'expectedNames' => ['bob', 'carl'],
            ],
            'filter on multiple same date time immutable' => [
                'criteria' => ['birth =' => [new DateTimeImmutable('2000-01-01 00:00:00')]],
                'expectedNames' => ['alice'],
            ],
            'filter on multiple same date time mutable' => [
                'criteria' => ['birth =' => [new DateTime('2000-01-01 00:00:00')]],
                'expectedNames' => ['alice'],
            ],
            'filter on multiple same float' => [
                'criteria' => ['score =' => [0.5, 1.0]],
                'expectedNames' => ['alice', 'bob'],
            ],
            'filter on multiple same bool' => [
                'criteria' => ['isMale =' => [true, false]],
                'expectedNames' => ['alice', 'bob', 'carl'],
            ],
            'filter on string or null' => [
                'criteria' => ['description =' => [null, 'Carl is a guy too']],
                'expectedNames' => ['alice', 'carl'],
            ],
            'filter on same string not found' => [
                'criteria' => ['name =' => 'devon'],
                'expectedNames' => [],
            ],
            'filter on same integer not found' => [
                'criteria' => ['count =' => 99],
                'expectedNames' => [],
            ],
            'filter on same float not found' => [
                'criteria' => ['score =' => 99.0],
                'expectedNames' => [],
            ],
            'filter on same date time immutable not found' => [
                'criteria' => ['birth =' => new DateTimeImmutable('1970-01-01 00:00:00')],
                'expectedNames' => [],
            ],
            'filter on same date time mutable not found' => [
                'criteria' => ['birth =' => new DateTime('1970-01-01 00:00:00')],
                'expectedNames' => [],
            ],
            'filter on multiple property 1/3' => [
                'criteria' => [
                    'name =' => 'alice',
                    'score =' => 0.5,
                ],
                'expectedNames' => ['alice'],
            ],
            'filter on multiple property 2/3' => [
                'criteria' => [
                    'name =' => 'alice',
                    'score =' => 99.0,
                ],
                'expectedNames' => [],
            ],
            'filter on multiple property 3/3' => [
                'criteria' => [
                    'count =' => 4,
                    'isMale =' => true,
                    'description =' => 'Bob is a guy',
                ],
                'expectedNames' => ['bob'],
            ],
            'filter on same enum' => [
                'criteria' => ['handSkill =' => HandSkillEnum::RIGHT],
                'expectedNames' => ['bob'],
            ],
        ];
    }
}
