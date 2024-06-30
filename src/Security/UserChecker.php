<?php

namespace App\Security;

use App\Entity\User as AppUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;

class UserChecker implements UserCheckerInterface
{

  public function __construct(private EntityManagerInterface $em)
  {
  }

  public function checkPreAuth(UserInterface $user): void
  {
    if (!$user instanceof AppUser) {
      return;
    }

    $authAttempt = $user->getAuthAttempt();

    if ($authAttempt && $authAttempt->getAttempt() > 4) {
      throw new CustomUserMessageAccountStatusException('Votre compte est bloqué dû à un trop grand nombre de tentatives de connexion.');
    }
  }

  public function checkPostAuth(UserInterface $user): void
  {
    if (!$user instanceof AppUser) {
      return;
    }

    $authAttempt = $user->getAuthAttempt();

    if ($authAttempt) {
      $authAttempt->setAttempt(0);
      $this->em->persist($authAttempt);
      $this->em->flush();
    }
  }
}