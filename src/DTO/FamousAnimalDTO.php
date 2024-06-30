<?php

namespace App\DTO;


class FamousAnimalDTO
{
  private int $id;
  private string $name;
  private int $clicks;

  /**
   * Get the value of id
   */
  public function getId(): int
  {
    return $this->id;
  }

  /**
   * Set the value of id
   */
  public function setId(int $id): self
  {
    $this->id = $id;

    return $this;
  }

  /**
   * Get the value of name
   */
  public function getName(): string
  {
    return $this->name;
  }

  /**
   * Set the value of name
   */
  public function setName(string $name): self
  {
    $this->name = $name;

    return $this;
  }

  /**
   * Get the value of clicks
   */
  public function getClicks(): int
  {
    return $this->clicks;
  }

  /**
   * Set the value of clicks
   */
  public function setClicks(int $clicks): self
  {
    $this->clicks = $clicks;

    return $this;
  }
}
