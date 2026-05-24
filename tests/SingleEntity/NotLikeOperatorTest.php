<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Tests\SingleEntity;

class NotLikeOperatorTest extends AbstractTestSingleEntity
{
    protected const FILTER_ON_NULLABLE_STRING_NOT_LIKE = 'filter on nullable string not like';
    protected const FILTER_ON_NULLABLE_STRING_NOT_LIKE_ARRAY = 'filter on nullable string not like array';

    public static function provideSingleEntityCriteria(): array
    {
        return [
            'filter on single string not contain' => [
                'criteria' => ['name not_like' => '%a%'],
                'expectedNames' => ['bob'],
            ],
            'filter on multiple string not contain' => [
                'criteria' => ['name not_like' => ['%b%', '%r%']],
                'expectedNames' => ['alice'],
            ],
            'filter on single string not end' => [
                'criteria' => ['name not_like' => '%e'],
                'expectedNames' => ['bob', 'carl'],
            ],
            'filter on multiple string not end' => [
                'criteria' => ['name not_like' => ['%b', '%l']],
                'expectedNames' => ['alice'],
            ],
            'filter on single string not start' => [
                'criteria' => ['name not_like' => 'a%'],
                'expectedNames' => ['bob', 'carl'],
            ],
            'filter on multiple string not start' => [
                'criteria' => ['name not_like' => ['a%', 'b%']],
                'expectedNames' => ['carl'],
            ],
            self::FILTER_ON_NULLABLE_STRING_NOT_LIKE => [
                'criteria' => ['description not_like' => 'Bob%'],
                'expectedNames' => ['carl'],
            ],
            self::FILTER_ON_NULLABLE_STRING_NOT_LIKE_ARRAY => [
                'criteria' => ['description not_like' => ['Bob%']],
                'expectedNames' => ['carl'],
            ],
        ];
    }
}
