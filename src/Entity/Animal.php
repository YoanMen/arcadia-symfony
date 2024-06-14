<?php

namespace App\Entity;

use App\Repository\AnimalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AnimalRepository::class)]
class Animal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 80)]
    private ?string $name = null;

    #[ORM\Column(length: 100)]
    private ?string $slug = null;

    #[ORM\ManyToOne(inversedBy: 'animals')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Habitat $habitat = null;




    #[ORM\OneToMany(targetEntity: AnimalImage::class, mappedBy: 'animal', orphanRemoval: true, cascade: ['persist'])]
    #[Assert\Count(min: 1, minMessage: 'Vous devez au moins mettre 1 image pour l\'animal')]
    private Collection $animalImages;

    #[ORM\OneToOne(inversedBy: 'animal', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?AnimalInformation $information = null;

    /**
     * @var Collection<int, AnimalReport>
     */
    #[ORM\OneToMany(targetEntity: AnimalReport::class, mappedBy: 'animal', orphanRemoval: true)]
    private Collection $animalReports;


    public function __construct()
    {
        $this->animalImages = new ArrayCollection();
        $this->animalReports = new ArrayCollection();
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getHabitat(): ?Habitat
    {
        return $this->habitat;
    }

    public function setHabitat(?Habitat $habitat): static
    {
        $this->habitat = $habitat;

        return $this;
    }

    /**
     * @return Collection<int, AnimalImage>
     */
    public function getAnimalImages(): Collection
    {
        return $this->animalImages;
    }

    public function addAnimalImage(AnimalImage $animalImage): static
    {
        if (!$this->animalImages->contains($animalImage)) {
            $this->animalImages->add($animalImage);
            $animalImage->setAnimal($this);
        }

        return $this;
    }

    public function removeAnimalImage(AnimalImage $animalImage): static
    {
        if ($this->animalImages->removeElement($animalImage)) {
            // set the owning side to null (unless already changed)
            if ($animalImage->getAnimal() === $this) {
                $animalImage->setAnimal(null);
            }
        }

        return $this;
    }

    public function getInformation(): ?AnimalInformation
    {
        return $this->information;
    }

    public function setInformation(AnimalInformation $information): static
    {
        $this->information = $information;

        return $this;
    }

    /**
     * @return Collection<int, AnimalReport>
     */
    public function getAnimalReports(): Collection
    {
        return $this->animalReports;
    }

    public function addAnimalReport(AnimalReport $animalReport): static
    {
        if (!$this->animalReports->contains($animalReport)) {
            $this->animalReports->add($animalReport);
            $animalReport->setAnimal($this);
        }

        return $this;
    }

    public function removeAnimalReport(AnimalReport $animalReport): static
    {
        if ($this->animalReports->removeElement($animalReport)) {
            // set the owning side to null (unless already changed)
            if ($animalReport->getAnimal() === $this) {
                $animalReport->setAnimal(null);
            }
        }

        return $this;
    }
}
