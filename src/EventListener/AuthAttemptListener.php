<?php

namespace App\EventListener;

use App\DTO\AccountLockDTO;
use App\Entity\AuthAttempt;
use App\Entity\User;
use App\Event\AccountLockedRequestEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Http\Event\LoginFailureEvent;

final class AuthAttemptListener
{
    public function __construct(private EntityManagerInterface $em, private EventDispatcherInterface $dispatcher)
    {
    }

    #[AsEventListener(event: LoginFailureEvent::class)]
    public function onSecurityAuthenticationError(LoginFailureEvent $event): void
    {
        $passport = $event->getPassport()->getUser();

        $user = $this->em->getRepository(User::class)
            ->findOneBy(['username' => $passport->getUserIdentifier()]);

        if (!$user) {
            return;
        }

        $authAttempt = $user->getAuthAttempt();

        $accountLockDTO = new AccountLockDTO();
        $accountLockDTO->username = $user->getUsername();
        $accountLockDTO->email = $user->getEmail();

        if ($authAttempt) {
            // increment attempt
            $attemptCount = $authAttempt->getAttempt() + 1;

            $authAttempt->setAttempt($attemptCount);

            // event to send mail to user
            if (5 == $attemptCount) {
                $this->dispatcher->dispatch(new AccountLockedRequestEvent($accountLockDTO));
            }
        } else {
            // create a new auth attempt for user
            $authAttempt = new AuthAttempt();

            $authAttempt->setAccount($user);
            $authAttempt->setAttempt(1);
        }

        $this->em->persist($authAttempt);
        $this->em->flush();
    }
}
