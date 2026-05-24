<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Tests\SingleEntity;

class LikeOperatorTest extends AbstractTestSingleEntity
{

    public static function provideSingleEntityCriteria(): array
    {
        return [
            'filter on contains string single result' => [
                'criteria' => ['name like' => '%lic%'],
                'expectedNames' => ['alice'],
            ],
            'filter on contains string multiple results' => [
                'criteria' => ['description like' => '%guy%'],
                'expectedNames' => ['bob', 'carl'],
            ],
            'filter on contains string not found' => [
                'criteria' => ['name like' => '%xyz%'],
                'expectedNames' => [],
            ],
            'filter on contains array of strings' => [
                'criteria' => ['name like' => ['%lic%', '%ob%']],
                'expectedNames' => ['alice', 'bob'],
            ],
            'filter on contains array of strings not found' => [
                'criteria' => ['name like' => ['%xyz%', '%123%']],
                'expectedNames' => [],
            ],
            'filter on contains multiple properties' => [
                'criteria' => [
                    'name like' => '%c%',
                    'description like' => '%guy%',
                ],
                'expectedNames' => ['carl'],
            ],
            'filter on ends string single result' => [
                'criteria' => ['name like' => '%ce'],
                'expectedNames' => ['alice'],
            ],
            'filter on ends string another single result' => [
                'criteria' => ['description like' => '%guy'],
                'expectedNames' => ['bob'],
            ],
            'filter on ends string not found' => [
                'criteria' => ['name like' => '%xyz'],
                'expectedNames' => [],
            ],
            'filter on ends array of strings' => [
                'criteria' => ['name like' => ['%ce', '%ob']],
                'expectedNames' => ['alice', 'bob'],
            ],
            'filter on ends array of strings not found' => [
                'criteria' => ['name like' => ['%xyz', '%123']],
                'expectedNames' => [],
            ],
            'filter on ends multiple properties' => [
                'criteria' => [
                    'name like' => '%rl',
                    'description like' => '%too',
                ],
                'expectedNames' => ['carl'],
            ],
            'filter on starts string single result' => [
                'criteria' => ['name like' => 'al%'],
                'expectedNames' => ['alice'],
            ],
            'filter on starts string not found' => [
                'criteria' => ['name like' => 'xyz%'],
                'expectedNames' => [],
            ],
            'filter on starts array of strings' => [
                'criteria' => ['name like' => ['al%', 'bo%']],
                'expectedNames' => ['alice', 'bob'],
            ],
            'filter on starts array of strings not found' => [
                'criteria' => ['name like' => ['xyz%', '123%']],
                'expectedNames' => [],
            ],
            'filter on starts multiple properties' => [
                'criteria' => [
                    'name like' => 'c%',
                    'description like' => 'Carl%',
                ],
                'expectedNames' => ['carl'],
            ],
        ];
    }
}
