<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Contract;

interface EnhancedFindByInterface
{
    /**
     * @template T of object
     * Type `T` is any entity type.
     *
     * Fetches a set of entities persisted in a database and matching the given parameters.
     * The parameters mirror a regular DQL query.
     *
     * The following example fetches every human whose first name starts with "Mary-",
     * born before the third millennium and working for 'The Deep Bottle' company.
     * The entities are ordered by their living city in descending order.
     * Only 30 entities are returned, starting with the 60th.
     *
     * <code>
     * $finder->findBy(
     *     from: Person::class,
     *     where: [
     *         'firstName like' => 'Mary-%',
     *         'birth <' => new DateTimeImmutable('2000-01-01'),
     *         'currentJob.company.name =' => 'The Deep Bottle', //Two implicit INNER JOINs are made here
     *     ],
     *     orderBy: ['address.city' => 'DESC'], //Another INNER JOIN is made here
     *     limit: 30,
     *     offset: 60,
     * );
     * </code>
     *
     * @param class-string<T> $from the class name of the entities to fetch,
     * retrieved from either the `get_class()` function or the `::class` keyword.
     * @param array<string, mixed> $where an ordered map where keys are property paths followed by an operator,
     *  mapped to the value to compare against.
     * @param array<string, string> $orderBy an ordered map where keys are property paths to use to sort the result set,
     * mapped to either 'ASC' or 'DESC'
     * When this parameter is empty, the underlying DBMS uses its default sort mechanism.
     * @param int|null $limit the maximum number of entities to fetch, greater than 0, or `null` to fetch every entity.
     * @param int $offset the number of entities to skip before starting to collect the result set,
     * greater than or equal to 0.
     * This parameter is ignored when `$limit` is `null`.
     * @return T[] the fetched entities. Every entity is unique.
     *
     * @throws EnhancedFindByInvalidArgumentException when any given argument is either invalid or forbidden.
     * @throws EnhancedFindByExceptionInterface when any error occurs.
     */
    public function findBy(
        string $from,
        array $where = [],
        array $orderBy = [],
        ?int $limit = null,
        int $offset = 0,
    ): array;
}
