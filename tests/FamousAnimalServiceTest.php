<?php

namespace App\Tests;

use App\Document\Animal;
use Psr\Log\LoggerInterface;
use PHPUnit\Framework\TestCase;
use App\Repository\AnimalRepository;
use App\Service\FamousAnimalService;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class FamousAnimalServiceTest extends TestCase
{
    public function testIncrementAnimalClick()
    {
        $animalId = 1;
        $repository = $this->createMock(DocumentRepository::class);
        $animalRepository = $this->createMock(AnimalRepository::class);
        $documentManager = $this->createMock(DocumentManager::class);
        $logger = $this->createMock(LoggerInterface::class);

        $animal = new Animal();
        $animal->setClick(0);
        $animal->setAnimalId($animalId);
        $animal->setName("test");

        $repository->expects(self::once())
            ->method('findOneBy')
            ->with(['animalId' => $animalId])
            ->willReturn($animal);

        $documentManager->expects(self::once())
            ->method('getRepository')
            ->with(Animal::class)
            ->willReturn($repository);

        $animalService = new FamousAnimalService($documentManager, $animalRepository, $logger);
        $animalService->incrementAnimalClick($animalId);

        $this->assertEquals(1, $animal->getClick());
    }
}
