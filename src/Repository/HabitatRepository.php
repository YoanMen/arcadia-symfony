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
    public function __construct(ManagerRegistry $registry, private AnimalRepository $animalRepository)
    {
        parent::__construct($registry, Habitat::class);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function findTwoHabitatForHomePageCards(): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT name, slug , description, image_name, alt FROM habitat
                INNER JOIN habitat_image ON habitat.id = habitat_image.habitat_id
                GROUP BY name
                LIMIT 2 ;';

        $conn->prepare($sql);

        return $conn->executeQuery($sql)->fetchAllAssociative();
    }

    /**
     * @return array<string, array<int, array<string, mixed>>|int>
     */
    public function findHabitatsByPage(int $page): array
    {
        $totalPages = ceil($this->count() / 6);
        $first = ($page - 1) * 6;

        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT name, slug, description, image_name, alt FROM habitat
                INNER JOIN habitat_image ON habitat.id = habitat_image.habitat_id
                GROUP BY name
                LIMIT 6 OFFSET $first";

        $conn->prepare($sql);

        $result['data'] = $conn->executeQuery($sql)
            ->fetchAllAssociative();

        $result['totalPage'] = intval($totalPages);

        return $result;
    }

    /**
     * @param string $slug habitat slug
     *
     * @return array<string, array<int, array<string, mixed>>|int>
     */
    public function findAnimalsByHabitatAndByPage(int $page, string $slug): array
    {
        $habitat = $this->findOneBy(['slug' => $slug]);
        $totalPages = ceil($this->animalRepository->count(['habitat' => $habitat->getId()]) / 6);
        $first = ($page - 1) * 6;

        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT name, slug, description, image_name, alt FROM animal
                INNER JOIN animal_image ON animal.id = animal_image.animal_id
                INNER JOIN animal_information ON animal.information_id = animal_information.id
                WHERE animal.habitat_id = :habitat
                GROUP BY name
                LIMIT 6 OFFSET $first";

        $result['data'] = $conn->executeQuery($sql, ['habitat' => $habitat->getId()])
            ->fetchAllAssociative();
        $result['totalPage'] = intval($totalPages);

        return $result;
    }
}
