<?php

namespace App\EventSubscriber;

use App\Event\ContactRequestEvent;
use App\Event\NewUserRegisteredEvent;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MailingSubscriber implements EventSubscriberInterface
{

    public function __construct(private readonly MailerInterface $mailer)
    {
    }

    public function onContactRequestEvent(ContactRequestEvent $event): void
    {

        $data = $event->data;
        $mail = (new TemplatedEmail())
            ->to('contact@arcadia.com')
            ->subject("Message du formulaire de contact par " . $data->name)
            ->from('no-reply@arcadia.com')
            ->htmlTemplate('email/contact.html.twig')
            ->context(['data' => $data]);

        $this->mailer->send($mail);
    }

    public function onNewUserRegisteredEvent(NewUserRegisteredEvent $event): void
    {

        $data = $event->data;
        $mail = (new TemplatedEmail())
            ->to($data->email)
            ->subject("Zoo Arcadia - Votre compte")
            ->from('no-reply@arcadia.com')
            ->htmlTemplate('email/newAccount.html.twig')
            ->context(['data' => $data]);

        $this->mailer->send($mail);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ContactRequestEvent::class => 'onContactRequestEvent',
            NewUserRegisteredEvent::class => 'onNewUserRegisteredEvent'
        ];
    }
}
