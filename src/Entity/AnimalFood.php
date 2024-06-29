<?php

namespace App\Entity;

use App\Repository\AnimalFoodRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AnimalFoodRepository::class)]
class AnimalFood
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\DateTimeValidator()]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column(length: 60)]
    #[Assert\NotBlank()]
    #[Assert\Length(max: 60, maxMessage: 'Le text de la nourriture est trop long, 60 caractères maximum')]
    private ?string $food = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 3)]
    #[Assert\NotBlank()]
    #[Assert\Positive()]

    private ?string $quantity = null;



    #[ORM\ManyToOne(inversedBy: 'animalFood')]
    #[ORM\JoinColumn(nullable: true, onDelete: "SET NULL")]

    private ?User $employee = null;

    #[ORM\ManyToOne(inversedBy: 'animalFood')]
    #[Assert\NotNull(message: "Il faut sélectionner un animal")]
    private ?Animal $animal = null;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getFood(): ?string
    {
        return $this->food;
    }

    public function setFood(string $food): static
    {
        $this->food = strtolower($food);

        return $this;
    }

    public function getQuantity(): ?string
    {
        return $this->quantity;
    }

    public function setQuantity(string $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getEmployee(): ?User
    {
        return $this->employee;
    }

    public function setEmployee(?User $employee): static
    {
        $this->employee = $employee;

        return $this;
    }

    public function getAnimal(): ?Animal
    {
        return $this->animal;
    }

    public function setAnimal(?Animal $animal): static
    {
        $this->animal = $animal;

        return $this;
    }
}
