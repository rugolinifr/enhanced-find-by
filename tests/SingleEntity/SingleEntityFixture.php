<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Tests\SingleEntity;

use DateTimeImmutable;
use Rugolinifr\EnhancedFindBy\Tests\Shared\AbstractFixture;
use Rugolinifr\EnhancedFindBy\Tests\Shared\HandSkillEnum;
use Rugolinifr\EnhancedFindBy\Tests\Shared\PlaceEnum;

class SingleEntityFixture extends AbstractFixture
{
    public function createOwners(): void
    {
        $this->createAndPersistOwner(
            'alice',
            2,
            new DateTimeImmutable('2000-01-01 00:00:00'),
            0.5,
            false,
            null,
            HandSkillEnum::LEFT,
            50,
            'Antoinette street',
            false,
            new DateTimeImmutable('1750-01-01 15:00:00'),
            1.25,
            PlaceEnum::APARTMENT,
        );
        $this->createAndPersistOwner(
            'bob',
            4,
            new DateTimeImmutable('tomorrow'),
            1.0,
            true,
            'Bob is a guy',
            HandSkillEnum::RIGHT,
            100,
            'Boulevard of the invalids',
            false,
            new DateTimeImmutable('1887-10-01 15:00:00'),
            1.50,
            PlaceEnum::HOUSE,
        );
        $this->createAndPersistOwner(
            'carl',
            8,
            new DateTimeImmutable('yesterday'),
            1.5,
            true,
            'Carl is a guy too',
            HandSkillEnum::BOTH,
            150,
            'Christian avenue',
            true,
            new DateTimeImmutable('1915-11-01 15:00:00'),
            1.75,
            PlaceEnum::WAREHOUSE,
        );
        $this->entityManager->flush();
    }
}
