<?php

namespace App\Entity;

use App\Repository\AnimalInformationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AnimalInformationRepository::class)]
class AnimalInformation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank()]
    #[Assert\Length(max: 320, maxMessage: 'La description de l\'animal doit faire moins de 320 caractères')]

    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    #[Assert\Length(max: 255, maxMessage: 'Le texte de la taille et el poids doit faire moins de 255 caractères')]
    private ?string $sizeAndHeight = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    #[Assert\Length(max: 255, maxMessage: 'Le texte de l\'espérance de vie doit faire moins de 255 caractères')]
    private ?string $lifespan = null;



    #[ORM\ManyToOne(inversedBy: 'animalInformation')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull()]
    private ?UICN $uicn = null;

    #[ORM\ManyToOne(inversedBy: 'animalInformation')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull()]
    private ?Region $region = null;

    #[ORM\OneToOne(mappedBy: 'information', cascade: ['persist', 'remove'])]
    private ?Animal $animal = null;

    #[ORM\OneToOne(inversedBy: 'animalInformation', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\Valid()]

    private ?Species $species = null;



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
