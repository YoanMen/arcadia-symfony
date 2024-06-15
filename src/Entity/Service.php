<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ServiceRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ServiceRepository::class)]
#[UniqueEntity('name')]

class Service
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 80)]
    #[Assert\NotBlank()]
    #[Assert\Length(max: 80, maxMessage: 'Le nom est trop long, 80 caractères maximum')]
    private ?string $name = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'Le nom ne doit pas être vide')]
    #[Assert\Length(max: 80, maxMessage: 'Le slug est trop long, 100 caractères maximum')]
    private ?string $slug = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'La description ne doit pas être vide')]
    #[Assert\Length(max: 255, maxMessage: 'La description est trop longue, 255 caractères maximum')]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank()]
    #[Assert\Length(max: 500, maxMessage: 'Le texte d\'information  est trop long, 500 caractères maximum')]
    private ?string $information = null;

    /**
     * @var Collection<int, ServiceImage>
     */
    #[ORM\OneToMany(targetEntity: ServiceImage::class, mappedBy: 'service', orphanRemoval: true)]
    #[Assert\Count(min: 1, minMessage: 'Vous devez au moins mettre 1 image pour le service')]
    private Collection $serviceImages;

    public function __construct()
    {
        $this->serviceImages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getInformation(): ?string
    {
        return $this->information;
    }

    public function setInformation(string $information): static
    {
        $this->information = $information;

        return $this;
    }

    /**
     * @return Collection<int, ServiceImage>
     */
    public function getServiceImages(): Collection
    {
        return $this->serviceImages;
    }

    public function addServiceImage(ServiceImage $serviceImage): static
    {
        if (!$this->serviceImages->contains($serviceImage)) {
            $this->serviceImages->add($serviceImage);
            $serviceImage->setService($this);
        }

        return $this;
    }

    public function removeServiceImage(ServiceImage $serviceImage): static
    {
        if ($this->serviceImages->removeElement($serviceImage)) {
            // set the owning side to null (unless already changed)
            if ($serviceImage->getService() === $this) {
                $serviceImage->setService(null);
            }
        }

        return $this;
    }
}
