<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Tests\SingleEntity;

use DateTime;
use DateTimeImmutable;
use Rugolinifr\EnhancedFindBy\Tests\Shared\HandSkillEnum;
use Rugolinifr\EnhancedFindBy\Tests\Shared\PlaceEnum;

class GreaterThanOperatorTest extends AbstractTestSingleEntity
{

    public static function provideSingleEntityCriteria(): array
    {
        return [
            'filter on strictly greater string' => [
                'criteria' => ['name >' => 'alice'],
                'expectedNames' => ['bob', 'carl'],
            ],
            'filter on strictly greater integer' => [
                'criteria' => ['count >' => 4],
                'expectedNames' => ['carl'],
            ],
            'filter on strictly greater integer multiple results' => [
                'criteria' => ['count >' => 1],
                'expectedNames' => ['alice', 'bob', 'carl'],
            ],
            'filter on strictly greater integer not found' => [
                'criteria' => ['count >' => 8],
                'expectedNames' => [],
            ],
            'filter on strictly greater float' => [
                'criteria' => ['score >' => 0.5],
                'expectedNames' => ['bob', 'carl'],
            ],
            'filter on strictly greater float not found' => [
                'criteria' => ['score >' => 1.5],
                'expectedNames' => [],
            ],
            'filter on strictly greater date time immutable' => [
                'criteria' => ['birth >' => new DateTimeImmutable('2000-01-01 00:00:00')],
                'expectedNames' => ['bob', 'carl'],
            ],
            'filter on strictly greater date time mutable' => [
                'criteria' => ['birth >' => new DateTime('today')],
                'expectedNames' => ['bob'],
            ],
            'filter on strictly greater multiple properties' => [
                'criteria' => [
                    'count >' => 2,
                    'score >' => 1.0,
                ],
                'expectedNames' => ['carl'],
            ],
            'filter on greater than enum' => [
                'criteria' => ['handSkill >' => HandSkillEnum::RIGHT],
                'expectedNames' => ['carl'],
            ],
            'filter on greater than embeddable string' => [
                'criteria' => ['address->streetName >' => 'Boulevard of the invalids'],
                'expectedNames' => ['carl'],
            ],
            'filter on greater than embeddable integer' => [
                'criteria' => ['address->number >' => 50],
                'expectedNames' => ['bob', 'carl'],
            ],
            'filter on greater than embeddable date' => [
                'criteria' => ['address->creationDate >' => new DateTimeImmutable('1915-11-01 15:00:00')],
                'expectedNames' => [],
            ],
            'filter on greater than embeddable float' => [
                'criteria' => ['address->valueOverAveragePrice >' => 1.25],
                'expectedNames' => ['bob', 'carl'],
            ],
            'filter on greater than embeddable enum' => [
                'criteria' => ['address->placeKind >' => PlaceEnum::WAREHOUSE],
                'expectedNames' => [],
            ],
        ];
    }
}