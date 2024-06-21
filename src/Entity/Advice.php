<?php

namespace App\Entity;

use App\Repository\AdviceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AdviceRepository::class)]
class Advice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 60)]
    #[Assert\Length(max: 60, maxMessage: 'Le pseudo est trop long, 60 caractères maximum')]
    #[Assert\NotBlank()]
    #[Groups(['advice.approved', 'advice.create'])]
    private ?string $pseudo = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank()]
    #[Assert\Length(min: 10, minMessage: 'L\'avis doit faire 10 caractères minimum', max: 300, maxMessage: 'L\'avis est trop long, 300 caractères maximum')]
    #[Groups(['advice.approved', 'advice.create'])]
    private ?string $advice = null;

    #[ORM\Column]
    #[Assert\PositiveOrZero()]
    private ?bool $approved = null;

    public function __construct()
    {
        $this->setApproved(false);
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = strip_tags(trim($pseudo));

        return $this;
    }

    public function getAdvice(): ?string
    {
        return $this->advice;
    }

    public function setAdvice(string $advice): static
    {
        $this->advice = strip_tags(trim($advice));

        return $this;
    }

    public function isApproved(): ?bool
    {
        return $this->approved;
    }

    public function setApproved(bool $approved): static
    {
        $this->approved = $approved;

        return $this;
    }
}
