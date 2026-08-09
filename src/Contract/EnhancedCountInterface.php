<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Contract;

interface EnhancedCountInterface
{
    /**
     * @template T of object
     * Type `T` is any entity type.
     *
     * Counts the number of entities persisted in a database and matching the given parameters.
     * The parameters mirror a regular DQL query.
     *
     * The following example counts humans whose first name starts with "Mary-",
     * born before the third millennium and working for 'The Deep Bottle' company.
     *
     * <code>
     * $count = $finder->count(
     *     from: Person::class,
     *     where: [
     *         'firstName like' => 'Mary-%',
     *         'birth <' => new DateTimeImmutable('2000-01-01'),
     *         'currentJob.company.name =' => 'The Deep Bottle', //Two implicit INNER JOINs are made here
     *     ],
     * );
     * </code>
     *
     * @param class-string<T> $from the class name of the entities to count,
     * retrieved from either the `get_class()` function or the `::class` keyword.
     * @param array<string, mixed> $where an ordered map where keys are property paths followed by an operator,
     *  mapped to the value to compare against.
     * @return int the number of matching entities.
     *
     * @throws EnhancedCountInvalidArgumentException when any given argument is either invalid or forbidden.
     * @throws EnhancedCountExceptionInterface when any error occurs.
     */
    public function count(
        string $from,
        array $where = [],
    ): int;
}
