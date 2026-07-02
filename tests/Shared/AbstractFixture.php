<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Tests\Shared;

use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Rugolinifr\EnhancedFindBy\Tests\Entity\Address;
use Rugolinifr\EnhancedFindBy\Tests\Entity\Owner;
use Rugolinifr\EnhancedFindBy\Tests\Entity\Product;
use Rugolinifr\EnhancedFindBy\Tests\Entity\Store;

class AbstractFixture
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
    ) {
    }

    protected function createAndPersistOwner(
        string $name,
        int $count,
        DateTimeImmutable $birth,
        float $score,
        bool $isMale,
        ?string $description,
        HandSkillEnum $handSkill,
        int $addressNumber,
        string $streetName,
        bool $isInsideDomain,
        DateTimeImmutable $creationDate,
        float $valueOverAveragePrice,
        PlaceEnum $placeKind,
    ): Owner {
        $owner = new Owner();
        $owner
            ->setName($name)
            ->setCount($count)
            ->setBirth($birth)
            ->setScore($score)
            ->setIsMale($isMale)
            ->setDescription($description)
            ->setHandSkill($handSkill)
            ->setAddress((new Address())
                ->setNumber($addressNumber)
                ->setStreetName($streetName)
                ->setIsInsideDomain($isInsideDomain)
                ->setCreationDate($creationDate)
                ->setValueOverAveragePrice($valueOverAveragePrice)
                ->setPlaceKind($placeKind)
            )
        ;
        $this->entityManager->persist($owner);
        return $owner;
    }

    protected function createAndPersistStore(
        string $storeName,
        Owner $owner,
    ): Store {
        $store = (new Store())
            ->setName($storeName)
            ->setOwner($owner);
        $this->entityManager->persist($store);
        return $store;
    }

    protected function createAndPersistProduct(
        string $productName,
        Store $store,
    ): void {
        $product = (new Product())
            ->setName($productName)
            ->setStore($store);
        $this->entityManager->persist($product);
    }
}
