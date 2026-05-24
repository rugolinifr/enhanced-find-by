<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Tests\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Rugolinifr\EnhancedFindBy\Tests\Shared\HandSkillEnum;

#[ORM\Entity]
class Owner
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: 'integer')]
    private ?int $count = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?DateTimeImmutable $birth = null;

    #[ORM\Column(type: 'float')]
    private ?float $score = null;

    #[ORM\Column(type: 'boolean')]
    private ?bool $isMale = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(enumType: HandSkillEnum::class)]
    private HandSkillEnum $handSkill;

    /** @var Collection<int, Store> */
    #[ORM\OneToMany(targetEntity: Store::class, mappedBy: 'owner', orphanRemoval: true)]
    private Collection $stores;

    public function __construct()
    {
        $this->stores = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(int $count): self
    {
        $this->count = $count;

        return $this;
    }

    public function getBirth(): ?DateTimeImmutable
    {
        return $this->birth;
    }

    public function setBirth(DateTimeImmutable $birth): self
    {
        $this->birth = $birth;

        return $this;
    }

    public function getScore(): ?float
    {
        return $this->score;
    }

    public function setScore(float $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function isMale(): ?bool
    {
        return $this->isMale;
    }

    public function setIsMale(bool $isMale): self
    {
        $this->isMale = $isMale;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Store>
     */
    public function getStores(): Collection
    {
        return $this->stores;
    }

    public function addStore(Store $store): self
    {
        if (!$this->stores->contains($store)) {
            $this->stores->add($store);
            $store->setOwner($this);
        }

        return $this;
    }

    public function removeStore(Store $store): self
    {
        if ($this->stores->removeElement($store)) {
            // set the owning side to null (unless already changed)
            if ($store->getOwner() === $this) {
                $store->setOwner(null);
            }
        }

        return $this;
    }

    public function getHandSkill(): HandSkillEnum
    {
        return $this->handSkill;
    }

    public function setHandSkill(HandSkillEnum $handSkill): Owner
    {
        $this->handSkill = $handSkill;

        return $this;
    }

}