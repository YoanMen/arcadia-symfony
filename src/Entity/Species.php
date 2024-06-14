<?php

namespace App\Entity;

use App\Repository\SpeciesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SpeciesRepository::class)]
class Species
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 80)]
    private ?string $communName = null;

    #[ORM\Column(length: 60)]
    private ?string $genre = null;

    #[ORM\Column(length: 60)]
    private ?string $family = null;

    #[ORM\Column(length: 60)]
    private ?string $ordre = null;

    #[ORM\OneToOne(mappedBy: 'species', cascade: ['persist', 'remove'])]
    private ?AnimalInformation $animalInformation = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommunName(): ?string
    {
        return $this->communName;
    }

    public function setCommunName(string $communName): static
    {
        $this->communName = $communName;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): static
    {
        $this->genre = $genre;

        return $this;
    }

    public function getFamily(): ?string
    {
        return $this->family;
    }

    public function setFamily(string $family): static
    {
        $this->family = $family;

        return $this;
    }

    public function getOrdre(): ?string
    {
        return $this->ordre;
    }

    public function setOrdre(string $ordre): static
    {
        $this->ordre = $ordre;

        return $this;
    }



    public function __toString(): string
    {
        return $this->communName;
    }

    public function getAnimalInformation(): ?AnimalInformation
    {
        return $this->animalInformation;
    }

    public function setAnimalInformation(AnimalInformation $animalInformation): static
    {
        // set the owning side of the relation if necessary
        if ($animalInformation->getSpecies() !== $this) {
            $animalInformation->setSpecies($this);
        }

        $this->animalInformation = $animalInformation;

        return $this;
    }
}
