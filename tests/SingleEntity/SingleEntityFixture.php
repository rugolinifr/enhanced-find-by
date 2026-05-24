<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Tests\SingleEntity;

use DateTimeImmutable;
use Rugolinifr\EnhancedFindBy\Tests\Shared\AbstractFixture;
use Rugolinifr\EnhancedFindBy\Tests\Shared\HandSkillEnum;

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
        );
        $this->createAndPersistOwner(
            'bob',
            4,
            new DateTimeImmutable('tomorrow'),
            1.0,
            true,
            'Bob is a guy',
            HandSkillEnum::RIGHT,
        );
        $this->createAndPersistOwner(
            'carl',
            8,
            new DateTimeImmutable('yesterday'),
            1.5,
            true,
            'Carl is a guy too',
            HandSkillEnum::BOTH,
        );
        $this->entityManager->flush();
    }
}
