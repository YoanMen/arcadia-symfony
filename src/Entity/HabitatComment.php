<?php

namespace App\Entity;

use App\Repository\HabitatCommentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: HabitatCommentRepository::class)]
class HabitatComment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    #[Assert\DateValidator()]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    #[Assert\Length(max: 255, maxMessage: 'Le détail doit être de 255 caractères maximum ')]
    private ?string $detail = null;



    #[ORM\ManyToOne(inversedBy: 'habitatComments')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull()]
    private ?Habitat $habitat = null;

    #[ORM\ManyToOne(inversedBy: 'habitatComments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $veterinary = null;

    public function __construct()
    {
    }

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

    public function getDetail(): ?string
    {
        return $this->detail;
    }

    public function setDetail(string $detail): static
    {
        $this->detail = $detail;

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
