<?php

namespace App\Entity;

use App\Repository\HabitatCommentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HabitatCommentRepository::class)]
class HabitatComment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column(length: 255)]
    private ?string $detail = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'habitatComments')]
    private Collection $veterinary;

    public function __construct()
    {
        $this->veterinary = new ArrayCollection();
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

    /**
     * @return Collection<int, User>
     */
    public function getVeterinary(): Collection
    {
        return $this->veterinary;
    }

    public function addVeterinary(User $veterinary): static
    {
        if (!$this->veterinary->contains($veterinary)) {
            $this->veterinary->add($veterinary);
        }

        return $this;
    }

    public function removeVeterinary(User $veterinary): static
    {
        $this->veterinary->removeElement($veterinary);

        return $this;
    }
    
}
