<?php

namespace App\Entity;

use App\Repository\AnimalInformationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnimalInformationRepository::class)]
class AnimalInformation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 180)]
    private ?string $sizeAndHeight = null;

    #[ORM\Column(length: 100)]
    private ?string $lifespan = null;

    #[ORM\ManyToOne(inversedBy: 'animalInformation')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Species $species = null;

    #[ORM\ManyToOne(inversedBy: 'animalInformation')]
    #[ORM\JoinColumn(nullable: false)]
    private ?UICN $uicn = null;

    #[ORM\ManyToOne(inversedBy: 'animalInformation')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Region $region = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getSizeAndHeight(): ?string
    {
        return $this->sizeAndHeight;
    }

    public function setSizeAndHeight(string $sizeAndHeight): static
    {
        $this->sizeAndHeight = $sizeAndHeight;

        return $this;
    }

    public function getLifespan(): ?string
    {
        return $this->lifespan;
    }

    public function setLifespan(string $lifespan): static
    {
        $this->lifespan = $lifespan;

        return $this;
    }

    public function getSpecies(): ?Species
    {
        return $this->species;
    }

    public function setSpecies(?Species $species): static
    {
        $this->species = $species;

        return $this;
    }

    public function getUicn(): ?UICN
    {
        return $this->uicn;
    }

    public function setUicn(?UICN $uicn): static
    {
        $this->uicn = $uicn;

        return $this;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): static
    {
        $this->region = $region;

        return $this;
    }
}
