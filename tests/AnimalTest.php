<?php

namespace App\Tests;

use App\Entity\Animal;
use App\Entity\AnimalImage;
use App\Entity\Habitat;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AnimalTest extends KernelTestCase
{
    public function testAnimalIsNotValid(): void
    {
        self::bootKernel();
        $container = $this->getContainer();

        $animal = new Animal();

        $animal->setName('');
        $animal->setSlug('');
        $animal->setHabitat(new Habitat());
        $animal->addAnimalImage(new AnimalImage());
        $errors = $container->get('validator')->validate($animal);

        $this->assertCount(2, $errors);
    }

    public function testAnimalIsValid(): void
    {
        self::bootKernel();
        $container = $this->getContainer();

        $animal = new Animal();

        $animal->setName('leo');
        $animal->setSlug('leo');
        $animal->setHabitat(new Habitat());
        $animal->addAnimalImage(new AnimalImage());
        $errors = $container->get('validator')->validate($animal);

        $this->assertCount(0, $errors);
    }
}
