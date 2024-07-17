<?php

namespace App\Repository;

use App\Entity\Animal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Animal>
 */
class AnimalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Animal::class);
    }

    public function getCountBySearch(string $search): int
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT COUNT(DISTINCT animal.name) FROM animal
                INNER JOIN animal_information ON animal_information.id = animal.information_id
                INNER JOIN species ON species.id= animal_information.species_id
                INNER JOIN region ON region.id = animal_information.region_id
                INNER JOIN habitat ON habitat.id= animal.habitat_id
                WHERE animal.name LIKE :search
                OR species.family LIKE :search
                OR region.region LIKE :search
                OR habitat.name LIKE :search
                OR species.commun_name LIKE :search;';

        return $conn->executeQuery($sql, ['search' => $search])->fetchOne();
    }

    /**
     * @return array<string, array<int, array<string, mixed>>>
     */
    public function findAnimalBySearch(string $search, int $page): array
    {
        $search = '%'.$search.'%';

        $totalPages = ceil($this->getCountBySearch($search) / 6);
        $first = ($page - 1) * 6;
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT animal.name, animal.slug, habitat.slug as habitat_slug, animal_information.description, image_name, alt FROM animal
                INNER JOIN animal_image ON animal.id = animal_image.animal_id
                INNER JOIN animal_information ON animal_information.id = animal.information_id
                INNER JOIN species ON species.id= animal_information.species_id
                INNER JOIN region ON region.id = animal_information.region_id
                INNER JOIN habitat ON habitat.id= animal.habitat_id
                WHERE animal.name LIKE :search
                OR species.family LIKE :search
                OR region.region LIKE :search
                OR habitat.name LIKE :search
                OR species.commun_name LIKE :search
                GROUP BY name
                LIMIT 6 OFFSET $first;";

        $result['data'] = $conn->executeQuery($sql, ['search' => $search])
            ->fetchAllAssociative();
        $result['totalPage'] = intval($totalPages);

        return $result;
    }

    /**
     * @return array<string, array<int, array<string, mixed>>>
     */
    public function getPredictive(string $search): array
    {
        $search = '%'.$search.'%';
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT animal.name, habitat.name AS habitat, region.region, species.family , species.commun_name FROM animal
                LEFT JOIN animal_image ON animal.id = animal_image.animal_id
                LEFT JOIN animal_information ON animal_information.id = animal.information_id
                LEFT JOIN species ON species.id= animal_information.species_id
                LEFT JOIN region ON region.id = animal_information.region_id
                LEFT JOIN habitat ON habitat.id= animal.habitat_id
                WHERE animal.name LIKE :search
                OR species.family LIKE :search
                OR region.region LIKE :search
                OR habitat.name LIKE :search
                OR species.commun_name LIKE :search
                GROUP BY name;';

        $result['data'] = $conn->executeQuery($sql, ['search' => $search])
            ->fetchAllAssociative();

        return $result;
    }
}
