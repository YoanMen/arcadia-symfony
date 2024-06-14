<?php

namespace App\Entity;

use App\Repository\SchedulesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SchedulesRepository::class)]
class Schedules
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
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
