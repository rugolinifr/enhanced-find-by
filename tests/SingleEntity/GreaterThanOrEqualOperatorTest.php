<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Tests\SingleEntity;

use DateTime;
use DateTimeImmutable;
use Rugolinifr\EnhancedFindBy\Tests\Shared\HandSkillEnum;
use Rugolinifr\EnhancedFindBy\Tests\Shared\PlaceEnum;

class GreaterThanOrEqualOperatorTest extends AbstractTestSingleEntity
{

    public static function provideSingleEntityCriteria(): array
    {
        return [
            'filter on greater or equal string' => [
                'criteria' => ['name >=' => 'bob'],
                'expectedNames' => ['bob', 'carl'],
            ],
            'filter on greater or equal integer' => [
                'criteria' => ['count >=' => 4],
                'expectedNames' => ['bob', 'carl'],
            ],
            'filter on greater or equal integer multiple results' => [
                'criteria' => ['count >=' => 2],
                'expectedNames' => ['alice', 'bob', 'carl'],
            ],
            'filter on greater or equal integer not found' => [
                'criteria' => ['count >=' => 10],
                'expectedNames' => [],
            ],
            'filter on greater or equal float' => [
                'criteria' => ['score >=' => 1.0],
                'expectedNames' => ['bob', 'carl'],
            ],
            'filter on greater or equal float multiple results' => [
                'criteria' => ['score >=' => 0.5],
                'expectedNames' => ['alice', 'bob', 'carl'],
            ],
            'filter on greater or equal float not found' => [
                'criteria' => ['score >=' => 2.0],
                'expectedNames' => [],
            ],
            'filter on greater or equal date time immutable' => [
                'criteria' => ['birth >=' => new DateTimeImmutable('yesterday')],
                'expectedNames' => ['bob', 'carl'],
            ],
            'filter on greater or equal date time mutable' => [
                'criteria' => ['birth >=' => new DateTime('today')],
                'expectedNames' => ['bob'],
            ],
            'filter on greater or equal multiple properties' => [
                'criteria' => [
                    'count >=' => 4,
                    'score >=' => 1.0,
                ],
                'expectedNames' => ['bob', 'carl'],
            ],
            'filter on greater than or equal to or equal enum' => [
                'criteria' => ['handSkill >=' => HandSkillEnum::RIGHT],
                'expectedNames' => ['bob', 'carl'],
            ],
            'filter on greater than or equal to embeddable string' => [
                'criteria' => ['address->streetName >=' => 'Boulevard of the invalids'],
                'expectedNames' => ['bob', 'carl'],
            ],
            'filter on greater than or equal to embeddable integer' => [
                'criteria' => ['address->number >=' => 50],
                'expectedNames' => ['alice', 'bob', 'carl'],
            ],
            'filter on greater than or equal to embeddable date' => [
                'criteria' => ['address->creationDate >=' => new DateTimeImmutable('1915-11-01 15:00:00')],
                'expectedNames' => ['carl'],
            ],
            'filter on greater than or equal to embeddable float' => [
                'criteria' => ['address->valueOverAveragePrice >=' => 1.25],
                'expectedNames' => ['alice', 'bob', 'carl'],
            ],
            'filter on greater than or equal to embeddable enum' => [
                'criteria' => ['address->placeKind >=' => PlaceEnum::WAREHOUSE],
                'expectedNames' => ['carl'],
            ],
        ];
    }
}