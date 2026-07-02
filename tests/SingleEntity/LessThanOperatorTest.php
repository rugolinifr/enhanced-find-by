<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Tests\SingleEntity;

use DateTime;
use DateTimeImmutable;
use Rugolinifr\EnhancedFindBy\Tests\Shared\HandSkillEnum;
use Rugolinifr\EnhancedFindBy\Tests\Shared\PlaceEnum;

class LessThanOperatorTest extends AbstractTestSingleEntity
{

    public static function provideSingleEntityCriteria(): array
    {
        return [
            'filter on strictly less string' => [
                'criteria' => ['name <' => 'carl'],
                'expectedNames' => ['alice', 'bob'],
            ],
            'filter on strictly less integer' => [
                'criteria' => ['count <' => 4],
                'expectedNames' => ['alice'],
            ],
            'filter on strictly less integer multiple results' => [
                'criteria' => ['count <' => 8],
                'expectedNames' => ['alice', 'bob'],
            ],
            'filter on strictly less integer not found' => [
                'criteria' => ['count <' => 2],
                'expectedNames' => [],
            ],
            'filter on strictly less float' => [
                'criteria' => ['score <' => 1.5],
                'expectedNames' => ['alice', 'bob'],
            ],
            'filter on strictly less float multiple results' => [
                'criteria' => ['score <' => 2.0],
                'expectedNames' => ['alice', 'bob', 'carl'],
            ],
            'filter on strictly less date time immutable' => [
                'criteria' => ['birth <' => new DateTimeImmutable('today')],
                'expectedNames' => ['alice', 'carl'],
            ],
            'filter on strictly less date time mutable' => [
                'criteria' => ['birth <' => new DateTime('1990-01-01 00:00:00')],
                'expectedNames' => [],
            ],
            'filter on strictly less multiple properties' => [
                'criteria' => [
                    'count <' => 8,
                    'score <' => 1.0,
                ],
                'expectedNames' => ['alice'],
            ],
            'filter on less than enum' => [
                'criteria' => ['handSkill <' => HandSkillEnum::RIGHT],
                'expectedNames' => ['alice'],
            ],
            'filter on less than embeddable string' => [
                'criteria' => ['address->streetName <' => 'Boulevard of the invalids'],
                'expectedNames' => ['alice'],
            ],
            'filter on less than embeddable integer' => [
                'criteria' => ['address->number <' => 50],
                'expectedNames' => [],
            ],
            'filter on less than embeddable date' => [
                'criteria' => ['address->creationDate <' => new DateTimeImmutable('1915-11-01 15:00:00')],
                'expectedNames' => ['alice', 'bob'],
            ],
            'filter on less than embeddable float' => [
                'criteria' => ['address->valueOverAveragePrice <' => 1.25],
                'expectedNames' => [],
            ],
            'filter on less than embeddable enum' => [
                'criteria' => ['address->placeKind <' => PlaceEnum::WAREHOUSE],
                'expectedNames' => ['alice', 'bob'],
            ],
        ];
    }
}