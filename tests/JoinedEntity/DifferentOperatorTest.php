<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Tests\JoinedEntity;

use DateTimeImmutable;
use Rugolinifr\EnhancedFindBy\Tests\Entity\Owner;
use Rugolinifr\EnhancedFindBy\Tests\Entity\Store;
use Rugolinifr\EnhancedFindBy\Tests\Shared\HandSkillEnum;
use Rugolinifr\EnhancedFindBy\Tests\Shared\PlaceEnum;

class DifferentOperatorTest extends AbstractTestJoinedEntity
{

    public static function provideJoinedEntityManyToOneCriteria(): array
    {
        $ericStore = (new Store())
            ->setName('The Eric store');
        $fannyFirstStore = (new Store())
            ->setName('The Fanny first store');
        $fanny = (new Owner())
            ->setName('fanny');
        return [
            'filter by different store' => [
                'criteria' => ['store !=' => $ericStore],
                'expectedNames' => ['fig', 'feijoa', 'filbert', 'farkleberry'],
            ],
            'filter by store not null' => [
                'criteria' => ['store !=' => null],
                'expectedNames' => ['elderberry', 'fig', 'feijoa', 'filbert', 'farkleberry'],
            ],
            'filter by multiple different stores' => [
                'criteria' => ['store !=' => [$ericStore, $fannyFirstStore]],
                'expectedNames' => ['filbert', 'farkleberry'],
            ],
            'filter by different transitive string' => [
                'criteria' => ['store.name !=' => 'The Eric store'],
                'expectedNames' => ['fig', 'feijoa', 'filbert', 'farkleberry'],
            ],
            'filter by different multiple transitive string' => [
                'criteria' => ['store.owner.name !=' => ['eric']],
                'expectedNames' => ['fig', 'feijoa', 'filbert', 'farkleberry'],
            ],
            'filter by different transitive integer' => [
                'criteria' => ['store.owner.count !=' => 14],
                'expectedNames' => ['fig', 'feijoa', 'filbert', 'farkleberry'],
            ],
            'filter by different transitive float' => [
                'criteria' => ['store.owner.score !=' => 11.5],
                'expectedNames' => ['fig', 'feijoa', 'filbert', 'farkleberry'],
            ],
            'filter by different transitive boolean' => [
                'criteria' => ['store.owner.isMale !=' => true],
                'expectedNames' => ['fig', 'feijoa', 'filbert', 'farkleberry'],
            ],
            'filter by different transitive date' => [
                'criteria' => ['store.owner.birth !=' => new DateTimeImmutable('2005-02-02 12:00:00')],
                'expectedNames' => ['fig', 'feijoa', 'filbert', 'farkleberry'],
            ],
            'filter by different transitive entity' => [
                'criteria' => ['store.owner !=' => $fanny],
                'expectedNames' => ['elderberry'],
            ],
            'filter by different from enum' => [
                'criteria' => ['store.owner.handSkill !=' => HandSkillEnum::RIGHT],
                'expectedNames' => ['fig', 'feijoa', 'filbert', 'farkleberry'],
            ],
            'filter on different embeddable transitive string' => [
                'criteria' => ['store.owner.address->streetName !=' => 'Eiffel avenue'],
                'expectedNames' => ['fig', 'feijoa', 'filbert', 'farkleberry'],
            ],
            'filter on different embeddable transitive integer' => [
                'criteria' => ['store.owner.address->number !=' => 250],
                'expectedNames' => ['fig', 'feijoa', 'filbert', 'farkleberry'],
            ],
            'filter on different embeddable transitive boolean' => [
                'criteria' => ['store.owner.address->isInsideDomain !=' => false],
                'expectedNames' => ['fig', 'feijoa', 'filbert', 'farkleberry'],
            ],
            'filter on different embeddable transitive date' => [
                'criteria' => ['store.owner.address->creationDate !=' => new DateTimeImmutable('2009-07-01 15:00:00')],
                'expectedNames' => ['fig', 'feijoa', 'filbert', 'farkleberry'],
            ],
            'filter on different embeddable transitive float' => [
                'criteria' => ['store.owner.address->valueOverAveragePrice !=' => 0.99],
                'expectedNames' => ['fig', 'feijoa', 'filbert', 'farkleberry'],
            ],
            'filter on different embeddable transitive enum' => [
                'criteria' => ['store.owner.address->placeKind !=' => PlaceEnum::HOUSE],
                'expectedNames' => ['fig', 'feijoa', 'filbert', 'farkleberry'],
            ],
        ];
    }
}
