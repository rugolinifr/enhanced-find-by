<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Implementation\Shared;

interface ComparisonInterface
{
    /**
     * Returns this comparison as a Doctrine Query Language Where clause (e.g. `my_alias.my_property = :my_value`).
     *
     * Does not return the `WHERE` word.
     * Returns the compared value as a parameter (e.g. `:my_parameter`).
     */
    public function getWhereDql(Incrementor $incrementor): string;

    /**
     * Returns every Doctrine Query Language Join clause needed by this comparison to be complete
     * (e.g. `JOIN e0.my_property e1`).
     *
     * Each join contains the `JOIN` word.
     *
     * @return string[]
     */
    public function getJoinsDql(Incrementor $incrementor): array;
}
