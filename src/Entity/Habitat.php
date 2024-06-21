<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\HabitatRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: HabitatRepository::class)]
#[UniqueEntity('name')]

class Habitat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 80)]
    #[Assert\NotBlank()]
    #[Assert\Length(max: 80, maxMessage: 'Le nom  est trop long, 80 caractères maximum')]
    #[Groups('habitat.cards')]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank()]
    #[Assert\Length(max: 500, maxMessage: 'La description est trop longue, 500 caractères maximum')]
    #[Groups('habitat.cards')]
    private ?string $description = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank()]
    #[Assert\Length(max: 100, maxMessage: 'Le texte pour l\'url est trop long, 100 caractères maximum')]
    #[Groups('habitat.cards')]
    private ?string $slug = null;

    /**
     * @var Collection<int, Animal>
     */
    #[ORM\OneToMany(targetEntity: Animal::class, mappedBy: 'habitat')]
    private Collection $animals;

    /**
     * @var Collection<int, HabitatImage>
     */
    #[ORM\OneToMany(targetEntity: HabitatImage::class, mappedBy: 'habitat', orphanRemoval: true, cascade: ['persist'])]
    #[Assert\Count(min: 1, minMessage: 'Vous devez au moins mettre 1 image pour l\'habitat')]
    #[Assert\Valid()]
    private Collection $habitatImages;

    /**
     * @var Collection<int, HabitatComment>
     */
    #[ORM\OneToMany(targetEntity: HabitatComment::class, mappedBy: 'habitat', orphanRemoval: true)]
    private Collection $habitatComments;



    public function __construct()
    {
        $this->animals = new ArrayCollection();
        $this->habitatImages = new ArrayCollection();
        $this->habitatComments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection<int, Animal>
     */
    public function getAnimals(): Collection
    {
        return $this->animals;
    }

    public function addAnimal(Animal $animal): static
    {
        if (!$this->animals->contains($animal)) {
            $this->animals->add($animal);
            $animal->setHabitat($this);
        }

        return $this;
    }

    public function removeAnimal(Animal $animal): static
    {
        if ($this->animals->removeElement($animal)) {
            // set the owning side to null (unless already changed)
            if ($animal->getHabitat() === $this) {
                $animal->setHabitat(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, HabitatImage>
     */
    public function getHabitatImages(): Collection
    {
        return $this->habitatImages;
    }

    public function addHabitatImage(HabitatImage $habitatImage): static
    {
        if (!$this->habitatImages->contains($habitatImage)) {
            $this->habitatImages->add($habitatImage);
            $habitatImage->setHabitat($this);
        }

        return $this;
    }

    public function removeHabitatImage(HabitatImage $habitatImage): static
    {
        if ($this->habitatImages->removeElement($habitatImage)) {
            // set the owning side to null (unless already changed)
            if ($habitatImage->getHabitat() === $this) {
                $habitatImage->setHabitat(null);
            }
        }

        return $this;
    }


    /**
     * @return Collection<int, HabitatComment>
     */
    public function getHabitatComments(): Collection
    {
        return $this->habitatComments;
    }

    public function addHabitatComment(HabitatComment $habitatComment): static
    {
        if (!$this->habitatComments->contains($habitatComment)) {
            $this->habitatComments->add($habitatComment);
            $habitatComment->setHabitat($this);
        }

        return $this;
    }

    public function removeHabitatComment(HabitatComment $habitatComment): static
    {
        if ($this->habitatComments->removeElement($habitatComment)) {
            // set the owning side to null (unless already changed)
            if ($habitatComment->getHabitat() === $this) {
                $habitatComment->setHabitat(null);
            }
        }

        return $this;
    }


    public function __toString(): string
    {
        return  $this->getName();
    }
}
