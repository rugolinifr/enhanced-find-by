# Enhanced findBy() method

![Latest Stable Version](https://poser.pugx.org/rugolinifr/enhanced-find-by/v/stable)
![License](https://poser.pugx.org/rugolinifr/enhanced-find-by/license)
![PHP Version Require](https://poser.pugx.org/rugolinifr/enhanced-find-by/require/php)
![Coverage](https://img.shields.io/badge/coverage-97%25-brightgreen.svg)
![PHPStan](https://img.shields.io/badge/phpstan-level%208-brightgreen.svg)

This package provides an API similar to the famous Doctrine `findBy()` method,
but offering more capabilities to execute more complex (but still simple) queries.

## Context

The original Doctrine `findBy()` method is pretty limited:
- it provides only two SQL operators: `=` and `IN ()`,
- it filters only on the properties owned by the target entity,

whereas the *enhanced* `findBy()` method:
- offers many SQL operators: `=`, `IN ()`, `!=`, `NOT IN()`, `<`, `<=`, `>`, `>=`, `LIKE`, `NOT LIKE`, `IS NULL`,
  `IS NOT NULL`,
- filters on properties from other entities (see below),
- sorts the result set when needed,
- limits the number of fetched entities on demand.

When the enhanced `findBy()` method filters on properties from other entities,
it handles by itself every mandatory `INNER JOIN` clause,
removing the need for the developer to use the DQL or the `QueryBuilder` API.

## Installation

Run `composer require rugolinifr/enhanced-find-by`.

## Usage

Developers should use only:
- the `Rugolinifr\EnhancedFindBy\Factory\EnhancedFindByFactory` factory,
- any class from the `Rugolinifr\EnhancedFindBy\Contract` namespace.

Other classes are considered `@internal`.

Basic usage examples:
```php
$finder = (new \Rugolinifr\EnhancedFindBy\Factory\EnhancedFindByFactory())
    ->createEnhancedFindBy($entityManager);

// fetches every `Person` entity
$entities = $finder->findBy(Person::class);

// fetches every newborn `Person` entity using the "greater than" operator
$newborns = $finder->findBy(Person::class, ['birth >' => new \DateTimeImmutable('yesterday')]);

// fetches every newborn girl `Person`
$newborns = $finder->findBy(
    Person::class,
    [
        'birth >' => new \DateTimeImmutable('yesterday'),
        'sex =' => GenderEnum::GIRL,
    ],
);

// fetches every extra-European `Person` entity with implicit joins on two other entities
$extraEuropeans = $finder->findBy(Person::class, ['address.country.continent !=' => 'Europe']);

// fetches every `Person` entity having long black hair from a Doctrine embeddable
$blackHairedPersons = $finder->findBy(
    Person::class,
    [
        'hair->length >' => 20,
        'hair->color =' => 'black',  // Use arrow ("->") notation to filter against a Doctrine embeddable
    ],
);
```

### The `$from` parameter

The `$from` parameter takes the class name of the entity to fetch as argument.
As usual,
it may be retrieved from either the `get_class()` function or the `::class` keyword.

### The `$where` parameter

The `$where` parameter takes an array as argument.
This array is a sequence where each key is an existing entity property,
followed by a space, terminated by an *operator*.

On the other hand,
the value type is mixed and depends on the operator name and the property type.

The key-value pair represents a boolean expression;
an entity matching **every** expression will be returned by the `findBy()` method.
The expressions are translated into DQL in the order they are declared in the array.

For example,
the following PHP code:
```php
$finder->findBy(
    Person::class,
    [
        'name like' => 'John%',
        'birth <' => new DateTimeImmutable('2010-01-01'),
        'eyeColor !=' => 'blue',
        'address.country.continent =' => 'Europe',
    ],
);
```

produces the following DQL query:
```dql
SELECT e0
FROM Person e0
JOIN e0.address e1
JOIN e1.country e2
WHERE e0.name LIKE 'John%'
AND e0.birth < '2010-01-01'
AND e0.eyeColor != 'blue'
AND e2.continent = 'Europe'
```

#### The `=` operator

Returns `true` if and only if the property value equals the given value, `false` otherwise.

This operator accepts the following types: `string`, `int`, `float`, `bool`, `\BackedEnum`, `\DateTimeInterface`,
`null`, any entity type and finally an `array` of any type mentioned earlier.

Note that this operator handles null values using the `IS NULL` operator.

Note that comparing nullable properties returns the expected result
(the DBMS considers `NULL = 'foo'` as `unknown` which is not `true` and therefore the row is discarded).

Note that an `array` of values behaves like the `IN ()` operator,
but handles `null` values inside the array with a `IS NULL` operator.

Note that passing an entity type works on `*ToOne` associations,
but does not with `*ToMany` associations (as it is using the DQL).

#### The `!=` operator

Returns `true` if and only if the property value is different from the given value, `false` otherwise.

This operator has the same characteristics as the `=` operator.
However,
passing a `null` value (or an array having a `null` value)
as argument creates a comparison using the `IS NOT NULL` operator.

Note that this operator is not null-safe:
when comparing nullable properties,
it does not return the expected result
(the DBMS considers `NULL != 'foo'` as `unknown` which is not `true` and therefore the row is discarded).

Consider using the `!==` operator on nullable properties.

#### The `!==` operator

Returns `true` if and only if the property value is different from the given value, `false` otherwise.

This operator has the same characteristics as the `!=` operator,
but forbids `null` (or an array having `null`) as given argument.

On the other hand,
it is null-safe:
when comparing nullable properties,
it returns the expected result (`NULL != 'foo'` is `true` and therefore the row is returned).
Internally,
this is done by prepending the DQL condition with `property IS NULL OR`.

#### The `<` operator

Returns `true` if and only if the property value is strictly less than the given value, `false` otherwise.

This operator accepts the following types: `int`, `float`, `string`, `\BackedEnum` and `\DateTimeInterface`.

#### The `<=` operator

Returns `true` if and only if the property value is less than or equal to the given value, `false` otherwise.

This operator accepts the same types as the `<` operator.

#### The `>` operator

Returns `true` if and only if the property value is strictly greater than the given value, `false` otherwise.

This operator accepts the same types as the `<` operator.

#### The `>=` operator

Returns `true` if and only if the property value is greater than or equal to the given value, `false` otherwise.

This operator accepts the same types as the `<` operator.

#### The `like` operator

Returns `true` if and only if the property value contains the given string pattern, `false` otherwise.

This operator accepts `string` and `string[]` values only.

The `%` character acts as the `.*` regex expression.
Example: 

```php
// fetches every Person having a description starting with "Once upon a time"
$finder->findBy(Person::class, ['description like' => 'Once upon a time%']);
```

#### The `not_like` operator

Returns `true` if and only if the property value does not contain the given string pattern, `false` otherwise.

This operator accepts `string` and `string[]` values only.

See the `like` operator for information about the `%` character.

Note that this operator is not null-safe:
when comparing nullable properties,
it does not return the expected result
(the DBMS considers `NULL NOT LIKE 'foo'` as `unknown` which is not `true` and therefore the row is discarded).

Consider using the `n_not_like` operator on nullable properties.

#### The `n_not_like` operator

Returns `true` if and only if the property value is null or does not contain the given string pattern,
`false` otherwise.

This operator has the same characteristics as the `not_like` operator,
except it is null-safe (`NULL NOT LIKE 'foo'` is `true` and therefore the row is returned).
Internally,
this is done by prepending the DQL condition with `property IS NULL OR`.

### The `orderBy` parameter

The `$orderBy` parameter takes an array as argument.
This array is a sequence where each key is an existing entity property,
mapped to either the `"ASC"` or the `"DESC"` string.

For example,
the following invocation:
```php
$finder->findBy(
    from: Person::class,
    where: ['height <=' => 200],
    orderBy: [
        'address.country.continent' => 'ASC',
        'name' => 'DESC',
    ],
);
```

is converted to the following DQL query:
```dql
SELECT e0
FROM Person e0
JOIN e0.address e1
JOIN e1.country e2
WHERE e0.height <= 200
ORDER BY e2.continent ASC, e0.name DESC
```

### The `$limit` and `$offset` parameters

These parameters act as the `LIMIT` SQL clause.

For example,
the following PHP code:
```php
$finder->findBy(
    from: Person::class,
    limit: 30,
    offset: 60,
);
```

produces the following DQL query:
```dql
SELECT e0
FROM Person e0
```

and these two Doctrine methods:
```php
$query->setMaxResults(30)->setFirstResult(60);
```

Note that mixing any implicit join with the `limit` operator leads to the query being wrapped by a `Paginator`.
The latter is mandatory when the join targets a `*ToMany` relation to fetch consistent data.

### The `count()` method

Since `v1.2.*`,
it is possible to count the number of entities:

```php
$counter = (new \Rugolinifr\EnhancedFindBy\Factory\EnhancedFindByFactory())
    ->createEnhancedCount($entityManager);

$count = $counter->count(
    from: Person::class,
    where: [
        'name like' => 'John%',
        'eyeColor !=' => 'blue',
    ],
);
```

Both the `$from` and `$where` parameters behave the same as those from the `findBy()` method.

## Known limitations

The enhanced `findBy()` can't:
- perform `OUTER JOIN` clause,
- perform `OR` operator in `WHERE` clause,
- compare two entity properties.

The `QueryBuilder` is still needed for the use cases listed above.

## For maintainers

To contribute or to inspect the project,
`docker` is required:
```shell
docker compose up -d --build --force-recreate
docker compose exec php vendor/bin/phpunit tests
docker compose exec php php -d memory_limit=-1 /usr/local/bin/phpstan analyze
```

The test coverage may be checked with:
```shell
docker compose exec -e XDEBUG_MODE=coverage php \
    vendor/bin/phpunit --coverage-filter src --coverage-html .local/coverage tests
# now open the `.local/coverage/index.html` in the web browser
```

The test database may be opened with:
```shell
docker compose exec mysql mysql -u root -ppassword find_by
```

Also note that the Doctrine console tool is located at `bin/console`:
```shell
docker compose exec php bin/console orm:info
```