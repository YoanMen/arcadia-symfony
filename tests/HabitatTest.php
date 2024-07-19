<?php

namespace App\Tests;

use App\Entity\UICN;
use App\Entity\Animal;
use App\Entity\Region;
use App\Entity\Habitat;
use App\Entity\Species;
use App\Entity\AnimalImage;
use PHPUnit\Framework\TestCase;
use App\Entity\AnimalInformation;
use App\Entity\HabitatImage;

class HabitatTest extends TestCase
{

  public function testAddAnimalToHabitat()
  {
    $habitat = new Habitat();

    $habitat->setDescription("test description");
    $habitat->setName("name");
    $habitat->setSlug("slug");
    $image = new HabitatImage();
    $image->setImageName('placeholder.svg');
    $image->setAlt('image alt');

    $habitat->addHabitatImage($image);
    $animal = $this->mockAnimal();

    $habitat->addAnimal($animal);

    $this->assertTrue($habitat->getAnimals()[0] !== null);
  }

  public function testRemoveAnimalFromHabitat()
  {
    $habitat = new Habitat();

    $habitat->setDescription("test description");
    $habitat->setName("name");
    $habitat->setSlug("slug");
    $image = new HabitatImage();
    $image->setImageName('placeholder.svg');
    $image->setAlt('image alt');

    $habitat->addHabitatImage($image);
    $animal = $this->mockAnimal();

    $habitat->addAnimal($animal);
    $habitat->removeAnimal($animal);

    $this->assertTrue($habitat->getAnimals()[0] == null);
  }


  private function mockAnimal(): Animal
  {
    $animal = new Animal();

    $image = new AnimalImage();
    $image->setImageName('placeholder.svg');
    $image->setAlt('image alt');

    $animal->addAnimalImage($image);
    $animal->setName('tes');
    $animal->setSlug('animal');

    $specie = new Species();
    $specie->setCommunName('nom commun');
    $specie->setFamily('family');
    $specie->setGenre('genre');
    $specie->setOrdre('ordre');

    $uicn = new UICN();
    $region = new Region();
    $uicn->setUicn('vunérable');
    $region->setRegion('afrique');



    $information = new AnimalInformation();
    $information->setDescription('description de l\'animal');
    $information->setLifespan('durée de vie entre x et x');
    $information->setSizeAndHeight('la taille est de X et le poids de X');
    $information->setRegion($region);
    $information->setUicn($uicn);
    $information->setSpecies($specie);
    $animal->setInformation($information);

    return $animal;
  }
}
