<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Tests\SingleEntity;

use DateTime;
use DateTimeImmutable;
use Rugolinifr\EnhancedFindBy\Tests\Shared\HandSkillEnum;

class DifferentOperatorTest extends AbstractTestSingleEntity
{

    protected const FILTER_ON_DIFFERENT_NULLABLE_STRING_NULL_IS_NOT_TRUE =
        'filter on different nullable string (null is not true)';

    public static function provideSingleEntityCriteria(): array
    {
        return [
            'filter on different string' => [
                'criteria' => ['name !=' => 'alice'],
                'expectedNames' => ['bob', 'carl'],
            ],
            'filter on different integer' => [
                'criteria' => ['count !=' => 4],
                'expectedNames' => ['alice', 'carl'],
            ],
            'filter on different date time immutable' => [
                'criteria' => ['birth !=' => new DateTimeImmutable('2000-01-01 00:00:00')],
                'expectedNames' => ['bob', 'carl'],
            ],
            'filter on different date time mutable' => [
                'criteria' => ['birth !=' => new DateTime('2000-01-01 00:00:00')],
                'expectedNames' => ['bob', 'carl'],
            ],
            'filter on different float' => [
                'criteria' => ['score !=' => 1.5],
                'expectedNames' => ['alice', 'bob'],
            ],
            'filter on different bool 1/2' => [
                'criteria' => ['isMale !=' => false],
                'expectedNames' => ['bob', 'carl'],
            ],
            'filter on different bool 2/2' => [
                'criteria' => ['isMale !=' => true],
                'expectedNames' => ['alice'],
            ],
            'filter on different null value' => [
                'criteria' => ['description !=' => null],
                'expectedNames' => ['bob', 'carl'],
            ],
            'filter on multiple different string' => [
                'criteria' => ['name !=' => ['alice', 'bob']],
                'expectedNames' => ['carl'],
            ],
            'filter on multiple different integer' => [
                'criteria' => ['count !=' => [4, 8]],
                'expectedNames' => ['alice'],
            ],
            'filter on multiple different date time immutable' => [
                'criteria' => ['birth !=' => [new DateTimeImmutable('2000-01-01 00:00:00')]],
                'expectedNames' => ['bob', 'carl'],
            ],
            'filter on multiple different date time mutable' => [
                'criteria' => ['birth !=' => [new DateTime('2000-01-01 00:00:00')]],
                'expectedNames' => ['bob', 'carl'],
            ],
            'filter on multiple different float' => [
                'criteria' => ['score !=' => [0.5, 1.0]],
                'expectedNames' => ['carl'],
            ],
            'filter on multiple different bool' => [
                'criteria' => ['isMale !=' => [true, false]],
                'expectedNames' => [],
            ],
            'filter on different string or null' => [
                'criteria' => ['description !=' => [null, 'Carl is a guy too']],
                'expectedNames' => ['bob'],
            ],
            'filter on different null[]' => [
                'criteria' => ['description !=' => [null]],
                'expectedNames' => ['bob', 'carl'],
            ],
            'filter on different string not found' => [
                'criteria' => ['name !=' => 'devon'],
                'expectedNames' => ['alice', 'bob', 'carl'],
            ],
            'filter on different integer not found' => [
                'criteria' => ['count !=' => 99],
                'expectedNames' => ['alice', 'bob', 'carl'],
            ],
            'filter on different float not found' => [
                'criteria' => ['score !=' => 99.0],
                'expectedNames' => ['alice', 'bob', 'carl'],
            ],
            'filter on different date time immutable not found' => [
                'criteria' => ['birth !=' => new DateTimeImmutable('1970-01-01 00:00:00')],
                'expectedNames' => ['alice', 'bob', 'carl'],
            ],
            'filter on different date time mutable not found' => [
                'criteria' => ['birth !=' => new DateTime('1970-01-01 00:00:00')],
                'expectedNames' => ['alice', 'bob', 'carl'],
            ],
            'filter on different multiple property 1/3' => [
                'criteria' => [
                    'name !=' => 'alice',
                    'score !=' => 1.0,
                ],
                'expectedNames' => ['carl'],
            ],
            'filter on different multiple property 2/3' => [
                'criteria' => [
                    'name !=' => 'alice',
                    'score !=' => 99.0,
                ],
                'expectedNames' => ['bob', 'carl'],
            ],
            'filter on different multiple property 3/3' => [
                'criteria' => [
                    'count !=' => 2,
                    'isMale !=' => false,
                    'description !=' => 'Bob is a guy',
                ],
                'expectedNames' => ['carl'],
            ],
            self::FILTER_ON_DIFFERENT_NULLABLE_STRING_NULL_IS_NOT_TRUE => [
                'criteria' => ['description != ' => 'Bob is a guy'],
                'expectedNames' => ['carl'],
            ],
            'filter on different from enum' => [
                'criteria' => ['handSkill !=' => HandSkillEnum::RIGHT],
                'expectedNames' => ['alice', 'carl'],
            ],
        ];
    }
}