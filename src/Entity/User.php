<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $username = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    /**
     * @var Collection<int, AnimalReport>
     */
    #[ORM\ManyToMany(targetEntity: AnimalReport::class, mappedBy: 'veterinary')]
    private Collection $animalReports;

    /**
     * @var Collection<int, AnimalFood>
     */
    #[ORM\ManyToMany(targetEntity: AnimalFood::class, mappedBy: 'employee')]
    private Collection $animalFood;

    #[ORM\OneToOne(mappedBy: 'account', cascade: ['persist', 'remove'])]
    private ?AuthAttempt $authAttempt = null;

    /**
     * @var Collection<int, HabitatComment>
     */
    #[ORM\ManyToMany(targetEntity: HabitatComment::class, mappedBy: 'veterinary')]
    private Collection $habitatComments;

    public function __construct()
    {
        $this->habitatComments = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
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
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
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
            $animalReport->addVeterinary($this);
        }

        return $this;
    }

    public function removeAnimalReport(AnimalReport $animalReport): static
    {
        if ($this->animalReports->removeElement($animalReport)) {
            $animalReport->removeVeterinary($this);
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
            $animalFood->addEmployee($this);
        }

        return $this;
    }

    public function removeAnimalFood(AnimalFood $animalFood): static
    {
        if ($this->animalFood->removeElement($animalFood)) {
            $animalFood->removeEmployee($this);
        }

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
            $habitatComment->addVeterinary($this);
        }

        return $this;
    }

    public function removeHabitatComment(HabitatComment $habitatComment): static
    {
        if ($this->habitatComments->removeElement($habitatComment)) {
            $habitatComment->removeVeterinary($this);
        }

        return $this;
    }
}
