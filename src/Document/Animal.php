<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\ODM\MongoDB\Types\Type;

#[MongoDB\Document(collection: 'animals')]
class Animal
{
    #[ODM\Id]
    private string $id;
    #[ODM\Field(type: Type::INT)]
    private int $animalId;

    #[ODM\Field(type: Type::STRING)]
    private string $name;

    #[ODM\Field(type: Type::INT)]
    private int $click;

    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Set the value of  id.
     */
    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getAnimalId(): int
    {
        return $this->animalId;
    }

    /**
     * Set the value of animal id.
     */
    public function setAnimalId(int $id): self
    {
        $this->animalId = $id;

        return $this;
    }

    /**
     * Get the value of number.
     */
    public function getClick(): int
    {
        return $this->click;
    }

    /**
     * Set the value of number.
     */
    public function setClick(int $click): self
    {
        $this->click = $click;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
