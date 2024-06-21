<?php

namespace App\Repository;

use App\Entity\Habitat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Habitat>
 */
class HabitatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Habitat::class);
    }

    public function findTwoHabitatForHomePageCards()
    {

        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT name, slug , description, image_name, alt FROM habitat ';
        $sql .= 'INNER JOIN habitat_image ON habitat.id = habitat_image.habitat_id;';
        $sql .= 'INNER JOIN alt ON habitat.id = habitat_image.habitat_id;';


        $conn->prepare($sql);
        return $conn->executeQuery($sql)->fetchAllAssociative();
    }
}
