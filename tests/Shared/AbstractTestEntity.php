<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Tests\Shared;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Rugolinifr\EnhancedFindBy\Contract\EnhancedCountExceptionInterface;
use Rugolinifr\EnhancedFindBy\Contract\EnhancedCountInterface;
use Rugolinifr\EnhancedFindBy\Contract\EnhancedFindByExceptionInterface;
use Rugolinifr\EnhancedFindBy\Contract\EnhancedFindByInterface;
use Rugolinifr\EnhancedFindBy\Contract\EnhancedFindByInvalidArgumentException;
use Rugolinifr\EnhancedFindBy\Factory\EnhancedFindByFactory;
use Rugolinifr\EnhancedFindBy\Tests\Entity\Owner;
use Rugolinifr\EnhancedFindBy\Tests\Entity\Product;
use Rugolinifr\EnhancedFindBy\Tests\Entity\Store;
use RuntimeException;
use Throwable;

class AbstractTestEntity extends TestCase
{
    protected static EntityManagerInterface $entityManager;
    protected static EnhancedFindByInterface $finder;
    protected static EnhancedCountInterface $counter;

    /** @var Owner[]|Product[]  */
    protected ?array $resultSet = null;
    protected ?int $countResult = null;
    protected ?Throwable $lastException = null;

    public static function setUpBeforeClass(): void
    {
        exec('bin/console orm:schema-tool:drop --force -q');
        exec('bin/console orm:schema-tool:update --force -q');
        static::$entityManager = EntityManagerFactory::createEntityManager();
        $factory = new EnhancedFindByFactory();
        static::$finder = $factory->createEnhancedFindBy(static::$entityManager);
        static::$counter = $factory->createEnhancedCount(static::$entityManager);
    }

    protected function givenIHaveAnEnhancedFindBy(): void
    {
    }

    protected function givenIHaveAnEnhancedCount(): void
    {
    }

    /**
     * @template T of object
     *
     * @param array<string, mixed> $criteria
     * @param class-string<T> $className
     */
    protected function whenISearchEntityByOperator(
        array $criteria,
        string $className,
    ): void {
        try {
            $criteria = $this->convertDullEntitiesByRealEntities($criteria);
            $this->resultSet = static::$finder->findBy($className, $criteria); //@phpstan-ignore assign.propertyType
        } catch (EnhancedFindByExceptionInterface|EnhancedFindByInvalidArgumentException $e) {
            $this->lastException = $e;
        }
    }

    /**
     * @template T of object
     *
     * @param array<string, mixed> $criteria
     * @param array<string, string> $orderBy
     * @param class-string<T> $from
     */
    protected function whenIOrderByEntity(
        array $criteria,
        array $orderBy,
        string $from,
    ): void {
        try {
            $this->resultSet = static::$finder->findBy($from, $criteria, $orderBy); //@phpstan-ignore assign.propertyType
        } catch (EnhancedFindByExceptionInterface|EnhancedFindByInvalidArgumentException $e) {
            $this->lastException = $e;
        }
    }

    /**
     * @param array<string, string> $orderBy
     */
    protected function whenISearchEntityWithLimit(array $orderBy, ?int $limit, int $offset): void
    {
        try {
            $this->resultSet = static::$finder->findBy(
                from: Owner::class,
                where: [],
                orderBy: $orderBy,
                limit: $limit,
                offset: $offset,
            );
        } catch (EnhancedFindByExceptionInterface|EnhancedFindByInvalidArgumentException $e) {
            $this->lastException = $e;
        }
    }

    protected function whenICountEntities(
        string $classname,
        array $where,
    ): void {
        try {
            $this->countResult = static::$counter->count($classname, $where);
        } catch (EnhancedCountExceptionInterface $e) {
            $this->lastException = $e;
        }
    }

    /**
     * @param array<string, mixed> $criteria
     * @return array<string, mixed>
     */
    private function convertDullEntitiesByRealEntities(array $criteria): array
    {
        $converter = function (mixed &$value): void {
            if ($value instanceof Owner || $value instanceof Store || $value instanceof Product) {
                $entityName = $value->getName();
                if ($entityName === null) {
                    throw new RuntimeException('The dull entity to convert has no name.');
                }
                $value = $this->fetchRealEntity($entityName, $value::class);
            }
        };
        array_walk_recursive($criteria, $converter);
        return $criteria;
    }

    /**
     * @template T of object
     *
     * @param class-string<T> $className
     */
    private function fetchRealEntity(string $entityName, string $className): Owner|Store|Product
    {
        $entities = static::$entityManager
            ->getRepository($className)
            ->findBy(['name' => $entityName])
        ;
        if (empty($entities)) {
            $msg = "There is no entity \"$className\" named \"$entityName\". Fixtures are broken.";
            throw new RuntimeException($msg);
        }
        if (2 <= count($entities)) {
            $msg = "There is more than one entity \"$className\" named \"$entityName\". Fixtures are broken.";
            throw new RuntimeException($msg);
        }
        return $entities[0]; //@phpstan-ignore return.type
    }

    /**
     * @param string[] $expectedNames
     */
    protected function thenIGetExpectedEntities(
        array $expectedNames,
        string $entityName,
    ): void {
        $this->assertNull(
            $this->lastException,
            "Enhanced findBy() throw an exception: {$this->lastException?->getMessage()}",
        );
        $this->assertIsArray(
            $this->resultSet,
            "Enhanced findBy() did not return an array of $entityName."
        );
        $this->assertCount(
            count($expectedNames),
            $this->resultSet,
            "Enhanced findBy() did not return the expected number of $entityName.",
        );
        foreach ($expectedNames as $index => $expectedName) {
            $entity = $this->resultSet[$index] ?? null;
            $this->assertNotNull(
                $entity,
                "Enhanced findBy() did not return an array with numeric keys, or expected names are broken."
            );
            $name = $entity->getName();
            $this->assertSame(
                $expectedName,
                $name,
                "Enhanced findBy() did not return the expected $entityName.",
            );
        }
    }
}
