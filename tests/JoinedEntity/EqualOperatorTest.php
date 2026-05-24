<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Tests\JoinedEntity;

use DateTimeImmutable;
use Rugolinifr\EnhancedFindBy\Tests\Entity\Owner;
use Rugolinifr\EnhancedFindBy\Tests\Entity\Store;
use Rugolinifr\EnhancedFindBy\Tests\Shared\HandSkillEnum;

class EqualOperatorTest extends AbstractTestJoinedEntity
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
            'filter by same store' => [
                'criteria' => ['store =' => $ericStore],
                'expectedNames' => ['elderberry'],
            ],
            'filter by store null' => [
                'criteria' => ['store =' => null],
                'expectedNames' => [],
            ],
            'filter by multiple same stores' => [
                'criteria' => ['store =' => [$ericStore, $fannyFirstStore]],
                'expectedNames' => ['elderberry', 'fig', 'feijoa'],
            ],
            'filter by same transitive string' => [
                'criteria' => ['store.name =' => 'The Eric store'],
                'expectedNames' => ['elderberry'],
            ],
            'filter by same even more transitive string' => [
                'criteria' => ['store.owner.name =' => 'eric'],
                'expectedNames' => ['elderberry'],
            ],
            'filter by same even more multiple transitive string' => [
                'criteria' => ['store.owner.name =' => ['damian', 'eric', 'fanny']],
                'expectedNames' => ['elderberry', 'fig', 'feijoa', 'filbert', 'farkleberry'],
            ],
            'filter by same transitive integer' => [
                'criteria' => ['store.owner.count =' => 14],
                'expectedNames' => ['elderberry'],
            ],
            'filter by same multiple transitive integer' => [
                'criteria' => ['store.owner.count =' => [14, 18]],
                'expectedNames' => ['elderberry', 'fig', 'feijoa', 'filbert', 'farkleberry'],
            ],
            'filter by same transitive float' => [
                'criteria' => ['store.owner.score =' => 12.0],
                'expectedNames' => ['fig', 'feijoa', 'filbert', 'farkleberry'],
            ],
            'filter by same multiple transitive float' => [
                'criteria' => ['store.owner.score =' => [12.0]],
                'expectedNames' => ['fig', 'feijoa', 'filbert', 'farkleberry'],
            ],
            'filter by same transitive boolean 1/2' => [
                'criteria' => ['store.owner.isMale =' => true],
                'expectedNames' => ['elderberry'],
            ],
            'filter by same transitive boolean 2/2' => [
                'criteria' => ['store.owner.isMale =' => false],
                'expectedNames' => ['fig', 'feijoa', 'filbert', 'farkleberry'],
            ],
            'filter by same multiple transitive boolean 2/2' => [
                'criteria' => ['store.owner.isMale =' => [false, true]],
                'expectedNames' => ['elderberry', 'fig', 'feijoa', 'filbert', 'farkleberry'],
            ],
            'filter by same transitive date' => [
                'criteria' => ['store.owner.birth =' => new DateTimeImmutable('2005-02-02 12:00:00')],
                'expectedNames' => ['elderberry'],
            ],
            'filter by same multiple transitive date' => [
                'criteria' => ['store.owner.birth =' => [
                    new DateTimeImmutable('2005-02-02 12:00:00'),
                    new DateTimeImmutable('2005-03-03 12:00:00'),
                ]],
                'expectedNames' => ['elderberry', 'fig', 'feijoa', 'filbert', 'farkleberry'],
            ],
            'filter by same transitive entity' => [
                'criteria' => ['store.owner =' => $fanny],
                'expectedNames' => ['fig', 'feijoa', 'filbert', 'farkleberry'],
            ],
            'filter by same multiple transitive entity' => [
                'criteria' => ['store.owner =' => [$fanny]],
                'expectedNames' => ['fig', 'feijoa', 'filbert', 'farkleberry'],
            ],
            'filter by equal to enum' => [
                'criteria' => ['store.owner.handSkill =' => HandSkillEnum::RIGHT],
                'expectedNames' => ['elderberry'],
            ],
        ];
    }
}
