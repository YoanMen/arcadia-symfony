<?php

namespace App\DataFixtures;

use App\Entity\Advice;
use App\Entity\Animal;
use App\Entity\AnimalImage;
use App\Entity\AnimalInformation;
use App\Entity\Habitat;
use App\Entity\HabitatImage;
use App\Entity\Region;
use App\Entity\Schedules;
use App\Entity\Species;
use App\Entity\UICN;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $this->createSchedules($manager);
        $this->createUsers($manager);
        $habitats = $this->createHabitats($manager);
        $this->createAnimals($manager, $habitats);
        $this->createAdvices($manager);
        $this->createAdviceWithJavascript($manager);
    }

    private function createSchedules(ObjectManager $manager): void
    {
        $schedules = new Schedules();
        $schedules->setSchedules('remplacer par vos horaires');
        $manager->persist($schedules);

        $manager->flush();
    }

    private function createUsers(ObjectManager $manager): void
    {
        $users = [];

        $admin = new User();

        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setUsername('josé');
        $admin->setEmail('jose@arcadia.com');

        $hashPassword = $this->passwordHasher->hashPassword($admin, 'LeZooArcadia@01');
        $admin->setPassword($hashPassword);

        $users[] = $admin;

        $employee = new User();

        $employee->setRoles(['ROLE_EMPLOYEE']);
        $employee->setUsername('employe');
        $employee->setEmail('employee@arcadia.com');

        $hashPassword = $this->passwordHasher->hashPassword($employee, 'LeZooArcadia@01');
        $employee->setPassword($hashPassword);

        $users[] = $employee;

        foreach ($users as $user) {
            $manager->persist($user);
            $manager->flush();
        }
    }

    /**
     * Summary of createHabitats.
     *
     * @return array<Habitat>
     */
    private function createHabitats(ObjectManager $manager): array
    {
        $habitats = [];
        $savane = new Habitat();
        $savane->setName('Savane');
        $savane->setSlug('savane');
        $savane->setDescription('La savane est un endroit merveilleux');

        $savaneImage = new HabitatImage();
        $savaneImage->setImageName('placeholder.svg');
        $savaneImage->setAlt('image alt');

        $savane->addHabitatImage($savaneImage);

        $habitats[] = $savane;

        $jungle = new Habitat();
        $jungle->setName('Jungle');
        $jungle->setSlug('Jungle');
        $jungle->setDescription('La jungle est un endroit merveilleux');

        $jungleImage = new HabitatImage();
        $jungleImage->setImageName('placeholder.svg');
        $jungleImage->setAlt('image alt');

        $jungle->addHabitatImage($jungleImage);

        $habitats[] = $jungle;

        foreach ($habitats as $habitat) {
            $manager->persist($habitat);
            $manager->flush();
        }

        return $habitats;
    }

    private function createAdvices(ObjectManager $manager): void
    {
        $advices = [];

        for ($i = 0; $i < 6; ++$i) {
            $advice = new Advice();
            $advice->setAdvice('test d\'un avis poster par un utilisateur');
            $advice->setPseudo('Nom'.strval($i));
            $advice->setApproved(true);

            $advices[] = $advice;
        }

        foreach ($advices as $advice) {
            $manager->persist($advice);
            $manager->flush();
        }
    }

    /**
     * Summary of createAnimals.
     *
     * @param array<Habitat> $habitats
     */
    private function createAnimals(ObjectManager $manager, array $habitats): void
    {
        $animals = [];

        for ($i = 0; $i < 30; ++$i) {
            $animal = new Animal();

            $image = new AnimalImage();
            $image->setImageName('placeholder.svg');
            $image->setAlt('image alt');

            $animal->addAnimalImage($image);
            $animal->setName('animal'.strval($i));
            $animal->setSlug('animal'.strval($i));

            $specie = new Species();
            $specie->setCommunName('nom commun');
            $specie->setFamily('family');
            $specie->setGenre('genre');
            $specie->setOrdre('ordre');

            $uicn = new UICN();
            $region = new Region();
            $uicn->setUicn('vunérable');
            $region->setRegion('afrique');

            $manager->persist($uicn);
            $manager->flush();

            $manager->persist($region);
            $manager->flush();

            $information = new AnimalInformation();
            $information->setDescription('description de l\'animal');
            $information->setLifespan('durée de vie entre x et x');
            $information->setSizeAndHeight('la taille est de X et le poids de X');
            $information->setRegion($region);
            $information->setUicn($uicn);
            $information->setSpecies($specie);

            $animal->setHabitat($habitats[rand(0, 1)]);

            $animal->setInformation($information);
            $animals[] = $animal;
        }

        foreach ($animals as $animal) {
            $manager->persist($animal);
            $manager->flush();
        }
    }

    private function createAdviceWithJavascript(ObjectManager $manager): void
    {
        $advice = new Advice();
        $advice->setAdvice("<script>alert('XSS')</script>");
        $advice->setPseudo("<script>alert('XSS')</script>");
        $advice->setApproved(true);

        $manager->persist($advice);
        $manager->flush();
    }
}
