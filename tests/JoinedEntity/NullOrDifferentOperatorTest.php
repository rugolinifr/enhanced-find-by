<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Tests\JoinedEntity;

class NullOrDifferentOperatorTest extends DifferentOperatorTest
{

    public static function provideJoinedEntityManyToOneCriteria(): array
    {
        $parentData = parent::provideJoinedEntityManyToOneCriteria();
        return self::replaceOperatorString($parentData);
    }

    /**
     * @param array<string, array<string, mixed>> $parentData
     * @return array<string, array<string, mixed>>
     */
    private static function replaceOperatorString(array $parentData): array
    {
        $data = [];
        foreach ($parentData as $testName => $testData) {
            $hasNull = false;
            $newCriteria = [];
            foreach ($testData['criteria'] as $criteriaKey => $criteriaValue) {
                if ($criteriaValue === null || (is_array($criteriaValue) && in_array(null, $criteriaValue, true))) {
                    $hasNull = true;
                    break;
                }
                $newCriteria[str_replace('!=', '!==', $criteriaKey)] = $criteriaValue;
            }
            if ($hasNull) {
                continue;
            }
            $testData['criteria'] = $newCriteria;
            $data[$testName] = $testData;
        }
        return $data;
    }
}
