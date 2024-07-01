<?php

namespace App\Entity;

use App\Repository\UICNRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UICNRepository::class)]
class UICN
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    #[Assert\Length(max: 255, maxMessage: 'L\'UICN ne doit pas dépasser 255 caractères')]
    private ?string $uicn = null;

    /**
     * @var Collection<int, AnimalInformation>
     */
    #[ORM\OneToMany(targetEntity: AnimalInformation::class, mappedBy: 'uicn')]
    private Collection $animalInformation;

    public function __construct()
    {
        $this->animalInformation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUicn(): ?string
    {
        return $this->uicn;
    }

    public function setUicn(string $uicn): static
    {
        $this->uicn = $uicn;

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
            $animalInformation->setUicn($this);
        }

        return $this;
    }

    public function removeAnimalInformation(AnimalInformation $animalInformation): static
    {
        if ($this->animalInformation->removeElement($animalInformation)) {
            // set the owning side to null (unless already changed)
            if ($animalInformation->getUicn() === $this) {
                $animalInformation->setUicn(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getUicn();
    }
}
