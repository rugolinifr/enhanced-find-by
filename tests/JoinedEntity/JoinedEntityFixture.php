<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Tests\JoinedEntity;

use DateTimeImmutable;
use Rugolinifr\EnhancedFindBy\Tests\Shared\AbstractFixture;
use Rugolinifr\EnhancedFindBy\Tests\Shared\HandSkillEnum;

class JoinedEntityFixture extends AbstractFixture
{
    public function createEntities(): void
    {
        $this->createIsolatedOwner();
        $this->createOwnerWithOneStoreOneProduct();
        $this->createOwnerWithMultipleStoreMultipleProduct();
        $this->entityManager->flush();
    }

    private function createIsolatedOwner(): void
    {
        $this->createAndPersistOwner(
            'damian',
            12,
            new DateTimeImmutable('2005-01-01 12:00:00'),
            10.5,
            true,
            'Damian has no store',
            HandSkillEnum::LEFT,
        );
    }

    private function createOwnerWithOneStoreOneProduct(): void
    {
        $eric = $this->createAndPersistOwner(
            'eric',
            14,
            new DateTimeImmutable('2005-02-02 12:00:00'),
            11.5,
            true,
            'Eric has juste one store',
            HandSkillEnum::RIGHT,
        );
        $store = $this->createAndPersistStore('The Eric store', $eric);
        $this->createAndPersistProduct('elderberry', $store);
    }

    private function createOwnerWithMultipleStoreMultipleProduct(): void
    {
        $fanny = $this->createAndPersistOwner(
            'fanny',
            18,
            new DateTimeImmutable('2005-03-03 12:00:00'),
            12.0,
            false,
            'Fanny has many stores',
            HandSkillEnum::BOTH,
        );
        $firstStore = $this->createAndPersistStore('The Fanny first store', $fanny);
        $this->createAndPersistProduct('fig', $firstStore);
        $this->createAndPersistProduct('feijoa', $firstStore);
        $secondStore = $this->createAndPersistStore('The Fanny second store', $fanny);
        $this->createAndPersistProduct('filbert', $secondStore);
        $this->createAndPersistProduct('farkleberry', $secondStore);
    }
}