<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Tests\SingleEntity;

use DateTime;
use DateTimeImmutable;
use Rugolinifr\EnhancedFindBy\Tests\Shared\HandSkillEnum;
use Rugolinifr\EnhancedFindBy\Tests\Shared\PlaceEnum;

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
            'filter on less than or equal to embeddable string' => [
                'criteria' => ['address->streetName <=' => 'Boulevard of the invalids'],
                'expectedNames' => ['alice', 'bob'],
            ],
            'filter on less than or equal to embeddable integer' => [
                'criteria' => ['address->number <=' => 50],
                'expectedNames' => ['alice'],
            ],
            'filter on less than or equal to embeddable date' => [
                'criteria' => ['address->creationDate <=' => new DateTimeImmutable('1915-11-01 15:00:00')],
                'expectedNames' => ['alice', 'bob', 'carl'],
            ],
            'filter on less than or equal to embeddable float' => [
                'criteria' => ['address->valueOverAveragePrice <=' => 1.25],
                'expectedNames' => ['alice'],
            ],
            'filter on less than or equal to embeddable enum' => [
                'criteria' => ['address->placeKind <=' => PlaceEnum::WAREHOUSE],
                'expectedNames' => ['alice', 'bob', 'carl'],
            ],
        ];
    }
}