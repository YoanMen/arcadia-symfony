<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ContactDTO
{
  #[Assert\NotBlank]
  #[Assert\Length(
    min: 3,
    max: 200,
    minMessage: "Le nom doit avoir au minimum 3 caractères.",
    maxMessage: "Le nom doit avoir au maximum 200 caractères."
  )]
  public string $name = '';

  #[Assert\NotBlank]
  #[Assert\Email]
  #[Assert\Length(
    min: 3,
    max: 320,
    minMessage: "L'adresse email doit avoir au minimum 3 caractères.",
    maxMessage: "L'adresse email doit avoir au maximum 320 caractères."
  )]
  public string $email = '';

  #[Assert\NotBlank]
  public string $contactType = '';

  #[Assert\NotBlank]
  #[Assert\Length(
    min: 10,
    max: 500,
    minMessage: "Le message doit avoir au minimum 10 caractères.",
    maxMessage: "Le message doit avoir au maximum 500 caractères."
  )]
  public string $message = '';
}
