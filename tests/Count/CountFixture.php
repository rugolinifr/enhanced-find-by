<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Tests\Count;

use DateTimeImmutable;
use Rugolinifr\EnhancedFindBy\Tests\Entity\Owner;
use Rugolinifr\EnhancedFindBy\Tests\Shared\AbstractFixture;
use Rugolinifr\EnhancedFindBy\Tests\Shared\HandSkillEnum;
use Rugolinifr\EnhancedFindBy\Tests\Shared\PlaceEnum;

class CountFixture extends AbstractFixture
{

    public function createFixtures(): void
    {
        $this->createWarrenEntity();
        $this->createXavierEntities();
        $this->createYannEntities();
        $this->createZoeEntities();
        $this->entityManager->flush();
    }

    private function createWarren(): Owner
    {
        return $this->createAndPersistOwner(
            'warren',
            60,
            new DateTimeImmutable('2010-01-01 10:00:00'),
            60.5,
            true,
            null,
            HandSkillEnum::RIGHT,
            20,
            'will street',
            false,
            new DateTimeImmutable('1999-01-01 10:00:00'),
            1.25,
            PlaceEnum::APARTMENT,
        );
    }

    private function createXavier(): Owner
    {
        return $this->createAndPersistOwner(
            'xavier',
            70,
            new DateTimeImmutable('2011-01-01 10:00:00'),
            70.5,
            true,
            null,
            HandSkillEnum::RIGHT,
            20,
            'xtreme street',
            false,
            new DateTimeImmutable('2000-01-01 10:00:00'),
            1.25,
            PlaceEnum::APARTMENT,
        );
    }

    private function createYann(): Owner
    {
        return $this->createAndPersistOwner(
            'yann',
            80,
            new DateTimeImmutable('2012-01-01 10:00:00'),
            80.5,
            true,
            null,
            HandSkillEnum::LEFT,
            40,
            'yellow street',
            false,
            new DateTimeImmutable('2001-01-01 10:00:00'),
            1.125,
            PlaceEnum::HOUSE,
        );
    }

    private function createZoe(): Owner
    {
        return $this->createAndPersistOwner(
            'zoe',
            90,
            new DateTimeImmutable('2012-01-01 10:00:00'),
            90.5,
            false,
            null,
            HandSkillEnum::BOTH,
            60,
            'zip avenue',
            true,
            new DateTimeImmutable('2002-01-01 10:00:00'),
            1.0625,
            PlaceEnum::HOUSE,
        );
    }

    private function createWarrenEntity(): void
    {
        $this->createWarren();
    }

    private function createXavierEntities(): void
    {
        $xavier = $this->createXavier();
        $xavierStore = $this->createAndPersistStore("xavier's store", $xavier);
        $this->createAndPersistProduct("xavier's product", $xavierStore);
    }

    private function createYannEntities(): void
    {
        $yann = $this->createYann();
        $yannStore = $this->createAndPersistStore("yann's store", $yann);
        $this->createAndPersistProduct("yann's product", $yannStore);
    }

    private function createZoeEntities(): void
    {
        $zoe = $this->createZoe();
        $zoeStore = $this->createAndPersistStore("zoe's store", $zoe);
        $this->createAndPersistProduct('zoe first product', $zoeStore);
        $this->createAndPersistProduct('zoe second product', $zoeStore);
        $this->createAndPersistProduct('zoe third product', $zoeStore);
    }
}
