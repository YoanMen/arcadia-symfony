<?php

namespace App\Entity;

use App\Repository\AnimalReportRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AnimalReportRepository::class)]
class AnimalReport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    #[Assert\Date()]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column(length: 60)]
    #[Assert\NotBlank()]
    #[Assert\Length(max: 60, maxMessage: 'Le statut est trop long, 60 caractères maximum')]
    private ?string $statut = null;

    #[ORM\Column(length: 60)]
    #[Assert\NotBlank()]
    #[Assert\Length(max: 60, maxMessage: 'La nourriture  est trop longue, 60 caractères maximum')]
    private ?string $food = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 3)]
    #[Assert\NotBlank()]
    #[Assert\Positive()]
    private ?string $quantity = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 60, maxMessage: 'Le detail  est trop long, 255 caractères maximum')]
    private ?string $detail = null;

    #[ORM\ManyToOne(inversedBy: 'animalReports')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Animal $animal = null;

    #[ORM\ManyToOne(inversedBy: 'animalReports')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?User $veterinary = null;

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

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = strtolower($statut);

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

    public function getDetail(): ?string
    {
        return $this->detail;
    }

    public function setDetail(?string $detail): static
    {
        $this->detail = $detail;

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

    public function getVeterinary(): ?User
    {
        return $this->veterinary;
    }

    public function setVeterinary(?User $veterinary): static
    {
        $this->veterinary = $veterinary;

        return $this;
    }
}
