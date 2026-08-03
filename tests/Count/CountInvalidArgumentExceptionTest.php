<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Tests\Count;

use PHPUnit\Framework\Attributes\DataProvider;
use Rugolinifr\EnhancedFindBy\Contract\EnhancedCountInvalidArgumentException;
use Rugolinifr\EnhancedFindBy\Tests\Entity\Owner;
use Rugolinifr\EnhancedFindBy\Tests\Exception\FindByInvalidArgumentExceptionTest as FindByInvalidArgumentExceptionTestAlias;
use Rugolinifr\EnhancedFindBy\Tests\Shared\AbstractTestEntity;
use stdClass;

class CountInvalidArgumentExceptionTest extends AbstractTestEntity
{
    public function testInvalidFrom(): void
    {
        $this->givenIHaveAnEnhancedCount();
        $this->whenICountEntities(stdClass::class, []);
        $this->thenIGetInvalidArgumentException();
    }

    /**
     * @param array<string, array<string, mixed>> $criteria
     */
    #[DataProvider('provideInvalidWhereConditions')]
    public function testInvalidWhere(array $criteria): void
    {
        $this->givenIHaveAnEnhancedCount();
        $this->whenICountEntities(Owner::class, $criteria);
        $this->thenIGetInvalidArgumentException();
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public static function provideInvalidWhereConditions(): array
    {
        return FindByInvalidArgumentExceptionTestAlias::provideInvalidArgumentData();
    }

    private function thenIGetInvalidArgumentException(): void
    {
        $this->assertInstanceOf(
            EnhancedCountInvalidArgumentException::class,
            $this->lastException,
            'The enhanced count() method did not throw an InvalidArgumentException.'
        );
    }
}
