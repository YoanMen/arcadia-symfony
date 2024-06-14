<?php

namespace App\Entity;

use App\Repository\RegionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RegionRepository::class)]
class Region
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 120)]
    private ?string $region = null;

    /**
     * @var Collection<int, AnimalInformation>
     */
    #[ORM\OneToMany(targetEntity: AnimalInformation::class, mappedBy: 'region')]
    private Collection $animalInformation;

    public function __construct()
    {
        $this->animalInformation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(string $region): static
    {
        $this->region = $region;

        return $this;
    }

    /**
     * @return Collection<int, AnimalInformation>
     */
    public function getAnimalInformation(): Collection
    {
        return $this->animalInformation;
    }

    public function addAnimalInformation(AnimalInformation $animalInformation): static
    {
        if (!$this->animalInformation->contains($animalInformation)) {
            $this->animalInformation->add($animalInformation);
            $animalInformation->setRegion($this);
        }

        return $this;
    }

    public function removeAnimalInformation(AnimalInformation $animalInformation): static
    {
        if ($this->animalInformation->removeElement($animalInformation)) {
            // set the owning side to null (unless already changed)
            if ($animalInformation->getRegion() === $this) {
                $animalInformation->setRegion(null);
            }
        }

        return $this;
    }
}
