<?php

namespace App\Entity;

use App\Repository\SpeciesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SpeciesRepository::class)]
class Species
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 80)]
    #[Assert\NotBlank()]
    #[Assert\Length(max: 80, maxMessage: 'Le nom commun ne doit pas dépasser 80 caractères')]
    private ?string $communName = null;

    #[ORM\Column(length: 60)]
    #[Assert\NotBlank()]
    #[Assert\Length(max: 60, maxMessage: 'Le genre ne doit pas dépasser 60 caractères')]
    private ?string $genre = null;

    #[ORM\Column(length: 60)]
    #[Assert\NotBlank()]
    #[Assert\Length(max: 60, maxMessage: 'La famille ne doit pas dépasser 60 caractères')]
    private ?string $family = null;

    #[ORM\Column(length: 60)]
    #[Assert\NotBlank()]
    #[Assert\Length(max: 60, maxMessage: 'L\'ordre ne doit pas dépasser 60 caractères')]
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

    public function __toString(): string
    {
        return $this->communName;
    }
}
