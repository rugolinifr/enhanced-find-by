<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Tests\SingleEntity;

class NullOrNotLikeOperatorTest extends NotLikeOperatorTest
{

    /**
     * @inheritDoc
     */
    public static function provideSingleEntityCriteria(): array
    {
        $parentData = parent::provideSingleEntityCriteria();
        $data = self::replaceOperatorString($parentData);
        unset($data[self::FILTER_ON_NULLABLE_STRING_NOT_LIKE]);
        unset($data[self::FILTER_ON_NULLABLE_STRING_NOT_LIKE_ARRAY]);
        $data['filter on nullable string not like (null is true)'] = [
            'criteria' => ['description n_not_like' => 'Bob%'],
            'expectedNames' => ['alice', 'carl'],
        ];
        $data['filter on nullable string not like array (null is true)'] = [
            'criteria' => ['description n_not_like' => ['Bob%']],
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
                $newCriteria[str_replace('not_like', 'n_not_like', $criteriaKey)] = $criteriaValue;
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