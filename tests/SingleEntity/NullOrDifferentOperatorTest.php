<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Tests\SingleEntity;

class NullOrDifferentOperatorTest extends DifferentOperatorTest
{

    public static function provideSingleEntityCriteria(): array
    {
        $parentData = parent::provideSingleEntityCriteria();
        unset($parentData[static::FILTER_ON_DIFFERENT_NULLABLE_STRING_NULL_IS_NOT_TRUE]);
        $data = self::replaceOperatorString($parentData);
        $data['filter on different nullable string (null is true)'] = [
            'criteria' => ['description !==' => 'Bob is a guy'],
            'expectedNames' => ['alice', 'carl'],
        ];
        return $data;
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
