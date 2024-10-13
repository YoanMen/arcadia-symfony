<?php

namespace App\Tests;

use App\Entity\Animal;
use App\DTO\ContactDTO;
use App\DTO\NewUserDTO;
use App\DTO\AccountLockDTO;
use PHPUnit\Framework\TestCase;
use App\Event\ContactRequestEvent;
use App\Repository\AnimalRepository;
use App\Service\FamousAnimalService;
use App\Event\NewUserRegisteredEvent;
use App\Event\AccountLockedRequestEvent;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\EventSubscriber\MailingSubscriber;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelEvents;
use App\EventSubscriber\PersistAnimalSubscriber;
use App\EventSubscriber\SecurityHeadersSubscriber;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;

class EventSubscriberTest extends TestCase
{

    public function testEventSubscriptions(): void
    {
        $this->assertArrayHasKey(KernelEvents::RESPONSE, SecurityHeadersSubscriber::getSubscribedEvents());
        $this->assertArrayHasKey(AfterEntityPersistedEvent::class, PersistAnimalSubscriber::getSubscribedEvents());
        $this->assertArrayHasKey(ContactRequestEvent::class, MailingSubscriber::getSubscribedEvents());
        $this->assertArrayHasKey(NewUserRegisteredEvent::class, MailingSubscriber::getSubscribedEvents());
        $this->assertArrayHasKey(AccountLockedRequestEvent::class, MailingSubscriber::getSubscribedEvents());
    }

    public function testResponseSetSecurityHeaders(): void
    {
        $subscriber = new SecurityHeadersSubscriber();

        $response = new Response();
        $kernel = $this->createMock(HttpKernelInterface::class);
        $request = $this->createMock(Request::class);
        $event = new ResponseEvent($kernel, $request, 0, $response);

        $subscriber->onKernelResponse($event);
        $this->assertTrue($response->headers->has('Strict-Transport-Security'));
    }

    public function testCreateAnimalDocumentWhenPersist(): void
    {
        $famousAnimalService = $this->createMock(FamousAnimalService::class);
        $animal = $this->createConfiguredMock(Animal::class, ['getId' => 1]);

        $subscriber = new PersistAnimalSubscriber($famousAnimalService);
        $afterEntityPersistedEvent = new AfterEntityPersistedEvent($animal);

        $famousAnimalService->expects($this->once())->method('createAnimal');
        $subscriber->createAnimalDocument($afterEntityPersistedEvent);
    }

    public function testContactSendMail(): void
    {
        $data = new ContactDTO();
        $data->name = 'First';
        $data->email = 'test@test.com';
        $data->message = 'message test';

        $contactRequestEvent = new ContactRequestEvent($data);
        $mailerInterface = $this->createMock(MailerInterface::class);
        $subscriber = new MailingSubscriber($mailerInterface);
        $mailerInterface->expects($this->once())->method('send');
        $subscriber->onContactRequestEvent($contactRequestEvent);
    }

    public function testSendMailToNewUser(): void
    {
        $data = new NewUserDTO();
        $data->email = 'test@test.com';
        $data->username = 'Fabien';

        $newUserRegisteredEvent = new NewUserRegisteredEvent($data);
        $mailerInterface = $this->createMock(MailerInterface::class);
        $subscriber = new MailingSubscriber($mailerInterface);
        $mailerInterface->expects($this->once())->method('send');
        $subscriber->onNewUserRegisteredEvent($newUserRegisteredEvent);
    }

    public function testSendMailToNewUserWhenAccountIsLocked(): void
    {
        $data = new AccountLockDTO();
        $data->email = 'test@test.com';
        $data->username = 'Fabien';

        $accountLockedRequestEvent = new AccountLockedRequestEvent($data);
        $mailerInterface = $this->createMock(MailerInterface::class);
        $subscriber = new MailingSubscriber($mailerInterface);
        $mailerInterface->expects($this->once())->method('send');
        $subscriber->onAccountLockedRequestEvent($accountLockedRequestEvent);
    }
}
