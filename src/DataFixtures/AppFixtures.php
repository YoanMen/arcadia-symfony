<?php

namespace App\DataFixtures;

use App\Entity\Schedules;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);


        $schedules = new Schedules();
        $schedules->setSchedules('remplacer par vos horaires');
        $manager->persist($schedules);

        $manager->flush();
    }
}
