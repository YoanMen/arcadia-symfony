<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity('email', message: 'Un compte avec cette adresse existe déjà')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank()]
    #[Assert\Length(max: 180, maxMessage: "Le nom de l\'utilisateur ne doit pas dépasser 180 caractères")]
    private ?string $username = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column(type: Types::JSON)]
    private array $roles = [];
    private string $selectedRole;

    /**
     * @var string The hashed password
     */
    #[ORM\Column(length: 60)]
    #[Assert\Regex('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,60}$/', message: 'Le mot de passe doit faire entre 8 et 60 caractères, avoir au minimum : une lettre majuscule, une lettre minuscules, un chiffre et un caractère spécial.')]
    private ?string $password = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank()]
    #[Assert\Email(message: "L'adresse email n'est pas valide")]
    // 255 caractères avant le @ et 55 pour le domaine.
    #[Assert\Length(max: 320, maxMessage: "L\'adresse email  ne doit pas dépasser 320 caractères")]
    private ?string $email = null;

    #[ORM\OneToOne(mappedBy: 'account', cascade: ['persist', 'remove'])]
    private ?AuthAttempt $authAttempt = null;

    /**
     * @var Collection<int, HabitatComment>
     */
    #[ORM\OneToMany(targetEntity: HabitatComment::class, mappedBy: 'veterinary')]
    private Collection $habitatComments;

    /**
     * @var Collection<int, AnimalFood>
     */
    #[ORM\OneToMany(targetEntity: AnimalFood::class, mappedBy: 'employee')]
    private Collection $animalFood;

    /**
     * @var Collection<int, AnimalReport>
     */
    #[ORM\OneToMany(targetEntity: AnimalReport::class, mappedBy: 'veterinary')]
    private Collection $animalReports;

    public function __construct()
    {
        $this->habitatComments = new ArrayCollection();
        $this->animalFood = new ArrayCollection();
        $this->animalReports = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSelectedRole(): string
    {
        return $this->selectedRole;
    }

    public function setSelectedRole(string $role): void
    {
        $this->selectedRole = $role;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function addRole(string $role): void
    {
        $this->roles[] = $role;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getAuthAttempt(): ?AuthAttempt
    {
        return $this->authAttempt;
    }

    public function setAuthAttempt(AuthAttempt $authAttempt): static
    {
        // set the owning side of the relation if necessary
        if ($authAttempt->getAccount() !== $this) {
            $authAttempt->setAccount($this);
        }

        $this->authAttempt = $authAttempt;

        return $this;
    }

    public function __toString(): string
    {
        return $this->username;
    }

    /**
     * @return Collection<int, HabitatComment>
     */
    public function getHabitatComments(): Collection
    {
        return $this->habitatComments;
    }

    public function addHabitatComment(HabitatComment $habitatComment): static
    {
        if (!$this->habitatComments->contains($habitatComment)) {
            $this->habitatComments->add($habitatComment);
            $habitatComment->setVeterinary($this);
        }

        return $this;
    }

    public function removeHabitatComment(HabitatComment $habitatComment): static
    {
        if ($this->habitatComments->removeElement($habitatComment)) {
            // set the owning side to null (unless already changed)
            if ($habitatComment->getVeterinary() === $this) {
                $habitatComment->setVeterinary(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, AnimalFood>
     */
    public function getAnimalFood(): Collection
    {
        return $this->animalFood;
    }

    public function addAnimalFood(AnimalFood $animalFood): static
    {
        if (!$this->animalFood->contains($animalFood)) {
            $this->animalFood->add($animalFood);
            $animalFood->setEmployee($this);
        }

        return $this;
    }

    public function removeAnimalFood(AnimalFood $animalFood): static
    {
        if ($this->animalFood->removeElement($animalFood)) {
            // set the owning side to null (unless already changed)
            if ($animalFood->getEmployee() === $this) {
                $animalFood->setEmployee(null);
            }
        }

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
            $animalReport->setVeterinary($this);
        }

        return $this;
    }

    public function removeAnimalReport(AnimalReport $animalReport): static
    {
        if ($this->animalReports->removeElement($animalReport)) {
            // set the owning side to null (unless already changed)
            if ($animalReport->getVeterinary() === $this) {
                $animalReport->setVeterinary(null);
            }
        }

        return $this;
    }
}
