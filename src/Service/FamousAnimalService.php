<?php

namespace App\Service;

use App\Document\Animal;
use App\Document\Animal as DocumentAnimal;
use App\Repository\AnimalRepository;
use Doctrine\ODM\MongoDB\DocumentManager;

class FamousAnimalService
{
    public function __construct(
        private DocumentManager $documentManager,
        private AnimalRepository $animalRepository,
    ) {
    }

    public function incrementAnimalClick(int $animalId): void
    {
        $repository = $this->documentManager->getRepository(Animal::class);

        $animal = $repository->findOneBy(['animalId' => $animalId]);

        if ($animal) {
            $click = $animal->getClick() + 1;
            $animal->setClick($click);

            $this->documentManager->persist($animal);
            $this->documentManager->flush();
        } else {
            throw new \Exception('cant add click to animal');
        }
    }

    public function updateAnimal(int $animalId): void
    {
        $repository = $this->documentManager->getRepository(Animal::class);

        $animal = $repository->findOneBy(['animalId' => $animalId]);
        $animalFromRepository = $this->animalRepository->findOneBy(['id' => $animalId]);

        if ($animal && $animalFromRepository) {
            $animal->setName($animalFromRepository->getName());

            $this->documentManager->persist($animal);
            $this->documentManager->flush();
        }
    }

    public function createAnimal(int $animalId): void
    {
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
    }

    public function deleteAnimal(int $animalId): void
    {
        $repository = $this->documentManager->getRepository(Animal::class);

        $animal = $repository->findOneBy(['animalId' => $animalId]);

        if ($animal) {
            $this->documentManager->remove($animal);
            $this->documentManager->flush();
        }
    }

    /**
     * return 5 famous animals.
     *
     * @return array<Animal>
     */
    public function getFamousAnimals(): array
    {
        $result = $this->documentManager->getRepository(DocumentAnimal::class)
            ->createQueryBuilder()
            ->sort(['click' => 'desc'])
            ->limit(5)
            ->getQuery()
            ->execute();

        return $result->toArray();
    }
}
