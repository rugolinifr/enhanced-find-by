<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Tests\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Rugolinifr\EnhancedFindBy\Tests\Shared\PlaceEnum;

#[ORM\Embeddable]
class Address
{
    #[ORM\Column(type: 'integer')]
    private int $number;

    #[ORM\Column(type: 'string')]
    private string $streetName;

    #[ORM\Column(type: 'boolean')]
    private bool $isInsideDomain;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $creationDate;

     #[ORM\Column(type: 'float')]
    private float $valueOverAveragePrice;

     #[ORM\Column(enumType: PlaceEnum::class)]
    private PlaceEnum $placeKind;

    public function getNumber(): int
    {
        return $this->number;
    }

    public function setNumber(int $number): Address
    {
        $this->number = $number;
        return $this;
    }

    public function getStreetName(): string
    {
        return $this->streetName;
    }

    public function setStreetName(string $streetName): Address
    {
        $this->streetName = $streetName;
        return $this;
    }

    public function isInsideDomain(): bool
    {
        return $this->isInsideDomain;
    }

    public function setIsInsideDomain(bool $isInsideDomain): Address
    {
        $this->isInsideDomain = $isInsideDomain;
        return $this;
    }

    public function getCreationDate(): DateTimeImmutable
    {
        return $this->creationDate;
    }

    public function setCreationDate(DateTimeImmutable $creationDate): Address
    {
        $this->creationDate = $creationDate;
        return $this;
    }

    public function getValueOverAveragePrice(): float
    {
        return $this->valueOverAveragePrice;
    }

    public function setValueOverAveragePrice(float $valueOverAveragePrice): Address
    {
        $this->valueOverAveragePrice = $valueOverAveragePrice;
        return $this;
    }

    public function getPlaceKind(): PlaceEnum
    {
        return $this->placeKind;
    }

    public function setPlaceKind(PlaceEnum $placeKind): Address
    {
        $this->placeKind = $placeKind;
        return $this;
    }
}
