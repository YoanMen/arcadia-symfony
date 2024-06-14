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

    #[ORM\Column(length: 255)]
    private ?string $sizeAndHeight = null;

    #[ORM\Column(length: 255)]
    private ?string $lifespan = null;



    #[ORM\ManyToOne(inversedBy: 'animalInformation')]
    #[ORM\JoinColumn(nullable: false)]
    private ?UICN $uicn = null;

    #[ORM\ManyToOne(inversedBy: 'animalInformation')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Region $region = null;

    #[ORM\OneToOne(mappedBy: 'information', cascade: ['persist', 'remove'])]
    private ?Animal $animal = null;

    #[ORM\OneToOne(inversedBy: 'animalInformation', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Species $species = null;




    public function __construct()
    {
    }

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

    public function getAnimal(): ?Animal
    {
        return $this->animal;
    }

    public function setAnimal(Animal $animal): static
    {
        // set the owning side of the relation if necessary
        if ($animal->getInformation() !== $this) {
            $animal->setInformation($this);
        }

        $this->animal = $animal;

        return $this;
    }

    public function getSpecies(): ?Species
    {
        return $this->species;
    }

    public function setSpecies(Species $species): static
    {
        $this->species = $species;

        return $this;
    }
}
