<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Tests\Exception;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use Rugolinifr\EnhancedFindBy\Tests\Entity\Owner;
use Rugolinifr\EnhancedFindBy\Tests\Shared\AbstractTestEntity;
use Rugolinifr\EnhancedFindBy\Tests\Shared\ColorEnum;
use stdClass;

class InvalidArgumentExceptionTest extends AbstractTestEntity
{

    /**
     * @param array<string, mixed> $criteria
     */
    #[DataProvider('provideInvalidArgumentData')]
    public function testInvalidArgumentException(
        array $criteria,
    ): void {
        $this->givenIHaveAnEnhancedFindBy();
        $this->whenISearchEntityByOperator($criteria, Owner::class);
        $this->thenIGetInvalidArgumentException();
    }

    public static function provideInvalidArgumentData(): array
    {
        return [
            'unexpected object for = operator' => [
                'criteria' => ['name =' => new stdClass()],
            ],
            'unexpected closure for = operator' => [
                'criteria' => ['name =' => fn() => 0],
            ],
            'unexpected array of array for = operator' => [
                'criteria' => ['name =' => [['string']]],
            ],
            'unexpected unit enum for = operator' => [
                'criteria' => ['name =' => ColorEnum::BLUE],
            ],
            'unexpected object for != operator' => [
                'criteria' => ['name !=' => new stdClass()],
            ],
            'unexpected closure for != operator' => [
                'criteria' => ['name !=' => fn() => 0],
            ],
            'unexpected array of array for != operator' => [
                'criteria' => ['name !=' => [['string']]],
            ],
            'unexpected unit enum for != operator' => [
                'criteria' => ['name !=' => ColorEnum::BLUE],
            ],
            'unexpected object for !== operator' => [
                'criteria' => ['name !==' => new stdClass()],
            ],
            'unexpected closure for !== operator' => [
                'criteria' => ['name !==' => fn() => 0],
            ],
            'unexpected array of array for !== operator' => [
                'criteria' => ['name !==' => [['string']]],
            ],
            'unexpected null for !== operator' => [
                'criteria' => ['name !==' => null],
            ],
            'unexpected unit enum for !== operator' => [
                'criteria' => ['name !==' => ColorEnum::BLUE],
            ],
            'unexpected object for < operator' => [
                'criteria' => ['name <' => new stdClass()],
            ],
            'unexpected closure for < operator' => [
                'criteria' => ['name <' => fn() => 0],
            ],
            'unexpected null for < operator' => [
                'criteria' => ['name <' => null],
            ],
            'unexpected array for < operator' => [
                'criteria' => ['name <' => ['string']],
            ],
            'unexpected unit enum for < operator' => [
                'criteria' => ['name <' => ColorEnum::BLUE],
            ],
            'unexpected object for <= operator' => [
                'criteria' => ['name <=' => new stdClass()],
            ],
            'unexpected closure for <= operator' => [
                'criteria' => ['name <=' => fn() => 0],
            ],
            'unexpected null for <= operator' => [
                'criteria' => ['name <=' => null],
            ],
            'unexpected array for <= operator' => [
                'criteria' => ['name <=' => ['string']],
            ],
            'unexpected unit enum for <= operator' => [
                'criteria' => ['name <=' => ColorEnum::BLUE],
            ],
            'unexpected object for > operator' => [
                'criteria' => ['name >' => new stdClass()],
            ],
            'unexpected closure for > operator' => [
                'criteria' => ['name >' => fn() => 0],
            ],
            'unexpected null for > operator' => [
                'criteria' => ['name >' => null],
            ],
            'unexpected array for > operator' => [
                'criteria' => ['name >' => ['string']],
            ],
            'unexpected unit enum for > operator' => [
                'criteria' => ['name >' => ColorEnum::BLUE],
            ],
            'unexpected object for >= operator' => [
                'criteria' => ['name >=' => new stdClass()],
            ],
            'unexpected closure for >= operator' => [
                'criteria' => ['name >=' => fn() => 0],
            ],
            'unexpected null for >= operator' => [
                'criteria' => ['name >=' => null],
            ],
            'unexpected array for >= operator' => [
                'criteria' => ['name >=' => ['string']],
            ],
            'unexpected unit enum for >= operator' => [
                'criteria' => ['name >=' => ColorEnum::BLUE],
            ],
            'unexpected object for like operator' => [
                'criteria' => ['name like' => new stdClass()],
            ],
            'unexpected closure for like operator' => [
                'criteria' => ['name like' => fn() => 0],
            ],
            'unexpected null for like operator' => [
                'criteria' => ['name like' => null],
            ],
            'unexpected int for like operator' => [
                'criteria' => ['name like' => 42],
            ],
            'unexpected unit enum for like operator' => [
                'criteria' => ['name like' => ColorEnum::BLUE],
            ],
            'unexpected object for not_like operator' => [
                'criteria' => ['name not_like' => new stdClass()],
            ],
            'unexpected closure for not_like operator' => [
                'criteria' => ['name not_like' => fn() => 0],
            ],
            'unexpected null for not_like operator' => [
                'criteria' => ['name not_like' => null],
            ],
            'unexpected int for not_like operator' => [
                'criteria' => ['name not_like' => 42],
            ],
            'unexpected unit enum for not_like operator' => [
                'criteria' => ['name not_like' => ColorEnum::BLUE],
            ],
            'property path and operator are not a key string' => [
                'criteria' => ['string'],
            ],
            'forgotten operator' => [
                'criteria' => ['name' => 'string'],
            ],
            'unknown operator' => [
                'criteria' => ['name ~' => 'string'],
            ],
            'unknown property' => [
                'criteria' => ['unknown =' => 'string'],
            ],
            'too many fields in the property path' => [
                'criteria' => ['unknown = lol' => 'string'],
            ],
        ];
    }

    /**
     * @param mixed[] $orderBy
     */
    #[DataProvider('provideInvalidOrderBY')]
    public function testInvalidArgumentExceptionOnOrderBy(array $orderBy): void
    {
        $this->givenIHaveAnEnhancedFindBy();
        $this->whenISearchEntityWithLimit($orderBy, 99, 0);
        $this->thenIGetInvalidArgumentException();
    }

    public static function provideInvalidOrderBY(): array
    {
        return [
            'order by is not string key' => [
                'orderBy' => ['ASC'],
            ],
            'order by is not string' => [
                'orderBy' => ['name' => 0],
            ],
            'order by is not ASC' => [
                'orderBy' => ['name' => 'LOL'],
            ],
            'order by has unknown property' => [
                'orderBy' => ['unknown' => 'DESC'],
            ],
        ];
    }

    #[DataProvider('provideInvalidLimit')]
    public function testInvalidArgumentExceptionOnLimit(int $limit, int $offset): void
    {
        $this->givenIHaveAnEnhancedFindBy();
        $this->whenISearchEntityWithLimit([], $limit, $offset);
        $this->thenIGetInvalidArgumentException();
    }

    public static function provideInvalidLimit(): array
    {
        return [
            'limit is negative' => [
                'limit' => -1,
                'offset' => 0,
            ],
            'limit is 0' => [
                'limit' => 0,
                'offset' => 0,
            ],
            'offset is negative' => [
                'limit' => 99,
                'offset' => -1,
            ],
        ];
    }

    private function thenIGetInvalidArgumentException(): void
    {
        $this->assertInstanceOf(
            InvalidArgumentException::class,
            $this->lastException,
            'The enhanced findBy() method did not throw an InvalidArgumentException.'
        );
    }
}
