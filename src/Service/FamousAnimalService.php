<?php

namespace App\Service;

use App\Document\Animal;
use App\Document\Animal as DocumentAnimal;
use App\Repository\AnimalRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Iterator\CachingIterator;
use Psr\Log\LoggerInterface;

class FamousAnimalService
{
    public function __construct(
        private DocumentManager $documentManager,
        private AnimalRepository $animalRepository,
        private LoggerInterface $logger,
    ) {
    }

    public function incrementAnimalClick(int $animalId): void
    {
        try {
            $repository = $this->documentManager->getRepository(Animal::class);

            $animal = $repository->findOneBy(['animalId' => $animalId]);

            if ($animal) {
                $click = $animal->getClick() + 1;
                $animal->setClick($click);

                $this->documentManager->persist($animal);
                $this->documentManager->flush();
            } else {
                throw new \Exception('animal not exist');
            }
        } catch (\Throwable $th) {
            $this->logger->error('Cant increment animal '.$th->getMessage());
        }
    }

    public function updateAnimal(int $animalId): void
    {
        try {
            $repository = $this->documentManager->getRepository(Animal::class);

            $animal = $repository->findOneBy(['animalId' => $animalId]);
            $animalFromRepository = $this->animalRepository->findOneBy(['id' => $animalId]);

            if ($animal && $animalFromRepository) {
                $animal->setName($animalFromRepository->getName());

                $this->documentManager->persist($animal);
                $this->documentManager->flush();
            }
        } catch (\Throwable $th) {
            $this->logger->error('Cant update animal');
        }
    }

    public function createAnimal(int $animalId): void
    {
        try {
            $repository = $this->documentManager->getRepository(Animal::class);

            $exist = $repository->findOneBy(['animalId' => $animalId]);
            $animalFromRepository = $this->animalRepository->findOneBy(['id' => $animalId]);

            if (!$exist && $animalFromRepository) {
                $animal = new Animal();
                $animal->setClick(0);
                $animal->setAnimalId($animalId);
                $animal->setName($animalFromRepository->getName());

                $this->documentManager->persist($animal);
                $this->documentManager->flush();
            }
        } catch (\Throwable $th) {
            $this->logger->error('Cant create animal');
        }
    }

    public function deleteAnimal(int $animalId): void
    {
        try {
            $repository = $this->documentManager->getRepository(Animal::class);

            $animal = $repository->findOneBy(['animalId' => $animalId]);

            if ($animal) {
                $this->documentManager->remove($animal);
                $this->documentManager->flush();
            }
        } catch (\Throwable $th) {
            $this->logger->error('cant delete animal document');
        }
    }

    /**
     * return 5 famous animals.
     *
     * @return array<Animal>
     */
    public function getFamousAnimals(): array
    {
        try {
            $result = $this->documentManager->getRepository(DocumentAnimal::class)
                ->createQueryBuilder()
                ->sort(['click' => 'desc'])
                ->limit(5)
                ->getQuery()
                ->execute();

            if ($result instanceof CachingIterator) {
                return $result->toArray();
            }

            throw new \Exception();
        } catch (\Throwable $th) {
            $this->logger->error('Cant get famous animals');

            return [];
        }
    }
}
