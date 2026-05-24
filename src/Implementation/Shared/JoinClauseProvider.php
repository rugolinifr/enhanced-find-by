<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Implementation\Shared;

class JoinClauseProvider
{
    /**
     * Returns every Doctrine Query Language Join clause needed by this comparison to be complete
     * (e.g. `JOIN e0.my_property e1`).
     *
     * Each join contains the `JOIN` word.
     *
     * @param string[] $splitPropertyPath the property to extract the needed `JOIN` clause from.
     * @return string[]
     */
    public function getJoinsDql(
        array $splitPropertyPath,
        Incrementor $incrementor,
    ): array {
        if (count($splitPropertyPath) < 2) {
            return [];
        }
        return $this->getEveryJoinClause($splitPropertyPath, $incrementor);
    }

    /**
     * @param string[] $splitPropertyPath
     * @return string[]
     */
    private function getEveryJoinClause(
        array $splitPropertyPath,
        Incrementor $incrementor,
    ): array {
        $propertyCount = count($splitPropertyPath);
        $previousAliases = ['e0'];
        $joins = [];
        for ($i = 0; $i < $propertyCount - 1; $i++) {
            $joins[] = $this->getJoinClause($splitPropertyPath, $incrementor, $i, $previousAliases);
        }
        return $joins;
    }

    /**
     * @param string[] $splitPropertyPath
     * @param string[] $previousAliases
     */
    private function getJoinClause(
        array $splitPropertyPath,
        Incrementor $incrementor,
        int $i,
        array &$previousAliases,
    ): string {
        $subSplit = array_slice($splitPropertyPath, 0, $i + 1);
        $subPath = implode('.', $subSplit);
        $alias = $incrementor->addPropertyPath($subPath);
        $previousAliases[] = $alias;
        $previousAlias = $previousAliases[$i];
        $property = $splitPropertyPath[$i];
        return "JOIN $previousAlias.$property $alias";
    }
}
