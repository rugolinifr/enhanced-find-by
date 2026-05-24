<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Tests\Shared;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
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

    /** @var Owner[]|Product[]  */
    protected ?array $resultSet = null;
    protected ?Throwable $lastException = null;

    public static function setUpBeforeClass(): void
    {
        exec('bin/console orm:schema-tool:drop --force -q');
        exec('bin/console orm:schema-tool:update --force -q');
        static::$entityManager = EntityManagerFactory::createEntityManager();
        static::$finder = (new EnhancedFindByFactory())->createEnhancedFindBy(static::$entityManager);
    }

    protected function givenIHaveAnEnhancedFindBy(): void
    {
    }

    /**
     * @param array<string, mixed> $criteria
     */
    protected function whenISearchEntityByOperator(
        array $criteria,
        string $className,
    ): void {
        try {
            $criteria = $this->convertDullEntitiesByRealEntities($criteria);
            $this->resultSet = static::$finder->findBy($className, $criteria);
        } catch (EnhancedFindByExceptionInterface|EnhancedFindByInvalidArgumentException $e) {
            $this->lastException = $e;
        }
    }

    /**
     * @param array<string, mixed> $criteria
     * @param array<string, string> $orderBy
     */
    protected function whenIOrderByEntity(
        array $criteria,
        array $orderBy,
        string $from,
    ): void {
        try {
            $this->resultSet = static::$finder->findBy($from, $criteria, $orderBy);
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

    /**
     * @param array<string, mixed> $criteria
     * @return array<string, mixed>
     */
    private function convertDullEntitiesByRealEntities(array $criteria): array
    {
        $converter = function (mixed &$value): void {
            if ($value instanceof Owner || $value instanceof Store || $value instanceof Product) {
                $value = $this->fetchRealEntity($value->getName(), $value::class);
            }
        };
        array_walk_recursive($criteria, $converter);
        return $criteria;
    }

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
        return $entities[0];
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
        $this->assertCount(
            count($expectedNames),
            $this->resultSet,
            "Enhanced findBy() did not return the expected number of $entityName.",
        );
        foreach ($expectedNames as $index => $expectedName) {
            $name = $this->resultSet[$index]->getName();
            $this->assertSame(
                $expectedName,
                $name,
                "Enhanced findBy() did not return the expected $entityName.",
            );
        }
    }
}
