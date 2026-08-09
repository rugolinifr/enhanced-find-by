<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Tests\JoinedEntity;

use DateTimeImmutable;
use Rugolinifr\EnhancedFindBy\Tests\Shared\AbstractFixture;
use Rugolinifr\EnhancedFindBy\Tests\Shared\HandSkillEnum;
use Rugolinifr\EnhancedFindBy\Tests\Shared\PlaceEnum;

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
            200,
            'Debeaux street',
            false,
            new DateTimeImmutable('1995-02-01 15:00:00'),
            1.3,
            PlaceEnum::APARTMENT,
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
            250,
            'Eiffel avenue',
            false,
            new DateTimeImmutable('2009-07-01 15:00:00'),
            0.99,
            PlaceEnum::HOUSE,
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
            300,
            'Flaubert avenue',
            true,
            new DateTimeImmutable('1922-09-01 15:00:00'),
            1.37,
            PlaceEnum::WAREHOUSE,
        );
        $firstStore = $this->createAndPersistStore('The Fanny first store', $fanny);
        $this->createAndPersistProduct('fig', $firstStore);
        $this->createAndPersistProduct('feijoa', $firstStore);
        $secondStore = $this->createAndPersistStore('The Fanny second store', $fanny);
        $this->createAndPersistProduct('filbert', $secondStore);
        $this->createAndPersistProduct('farkleberry', $secondStore);
    }

    public function createOwnerForLimitTest(): void
    {
        $georges = $this->createAndPersistOwner(
            'georges',
            22,
            new DateTimeImmutable('2005-04-04 12:00:00'),
            12.5,
            true,
            'Georges exist to test the limit clause with JOIN on ToMany association.',
            HandSkillEnum::BOTH,
            400,
            'Georgia avenue',
            true,
            new DateTimeImmutable('1970-10-01 15:00:00'),
            1.05,
            PlaceEnum::WAREHOUSE,
        );
        $store = $this->createAndPersistStore("The Georges store", $georges);
        $this->createAndPersistProduct('grape', $store);
        $this->entityManager->flush();
    }
}