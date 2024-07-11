<?php

namespace App\EventSubscriber;

use App\Entity\Animal;
use App\Service\FamousAnimalService;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PersistAnimalSubscriber implements EventSubscriberInterface
{
    public function __construct(private FamousAnimalService $famousAnimalService)
    {
    }

    public function createAnimalDocument(AfterEntityPersistedEvent $afterEntityUpdatedEvent): void
    {
        $entity = $afterEntityUpdatedEvent->getEntityInstance();

        if (!($entity instanceof Animal)) {
            return;
        }

        $this->famousAnimalService->createAnimal($entity->getId());
    }

    public static function getSubscribedEvents()
    {
        return [
            AfterEntityPersistedEvent::class => ['createAnimalDocument'],
        ];
    }
}
