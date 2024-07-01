<?php

namespace App\Entity;

use App\Repository\SchedulesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SchedulesRepository::class)]
class Schedules
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank()]
    #[Assert\Length(max: 500, maxMessage: 'Le texte des horaires ne doit pas dépasser 500 caractères')]
    private ?string $schedules = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSchedules(): ?string
    {
        return $this->schedules;
    }

    public function setSchedules(string $schedules): static
    {
        $this->schedules = $schedules;

        return $this;
    }
}
