<?php

namespace App\Entity;

use App\Repository\SpeciesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SpeciesRepository::class)]
class Species
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $species = null;

    /**
     * @var Collection<int, AnimalInformation>
     */
    #[ORM\OneToMany(targetEntity: AnimalInformation::class, mappedBy: 'species')]
    private Collection $animalInformation;

    public function __construct()
    {
        $this->animalInformation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSpecies(): ?string
    {
        return $this->species;
    }

    public function setSpecies(string $species): static
    {
        $this->species = $species;

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
            $animalInformation->setSpecies($this);
        }

        return $this;
    }

    public function removeAnimalInformation(AnimalInformation $animalInformation): static
    {
        if ($this->animalInformation->removeElement($animalInformation)) {
            // set the owning side to null (unless already changed)
            if ($animalInformation->getSpecies() === $this) {
                $animalInformation->setSpecies(null);
            }
        }

        return $this;
    }
}
