<?php

namespace App\Entity;

use App\Repository\AuthAttemptRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AuthAttemptRepository::class)]
class AuthAttempt
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(options: ['default' => 0])]
    private ?int $attempt = null;

    #[ORM\OneToOne(inversedBy: 'authAttempt', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $account = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAttempt(): ?int
    {
        return $this->attempt;
    }

    public function setAttempt(int $attempt): static
    {
        $this->attempt = $attempt;

        return $this;
    }

    public function getAccount(): ?User
    {
        return $this->account;
    }

    public function setAccount(User $account): static
    {
        $this->account = $account;

        return $this;
    }
}
