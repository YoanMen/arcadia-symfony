<?php

namespace App\EventSubscriber;

use App\Entity\Animal;
use App\Service\CouchDBManager;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PersistAnimalSubscriber implements EventSubscriberInterface
{
    public function __construct(private CouchDBManager $couchDBManager)
    {
    }

    public function createAnimalDocument(AfterEntityPersistedEvent $afterEntityUpdatedEvent): void
    {
        $entity = $afterEntityUpdatedEvent->getEntityInstance();

        if (!($entity instanceof Animal)) {
            return;
        }

        $this->couchDBManager->createAnimalDocument($entity->getId());
    }

    public static function getSubscribedEvents()
    {
        return [
            AfterEntityPersistedEvent::class => ['createAnimalDocument'],
        ];
    }
}
