<?php

namespace App\Repository;

use App\Entity\Service;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Service>
 */
class ServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Service::class);
    }


    public function findServicesByPage(int $page): array
    {

        $totalPages = ceil($this->count() / 9);
        $first = ($page) * 9;


        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT name, slug, description, image_name, alt FROM service ';
        $sql .= 'INNER JOIN service_image ON service.id = service_image.service_id;';
        $sql .= 'INNER JOIN alt ON service.id = service_image.service_id;';
        $sql .= 'LIMIT 9 OFFSET :page';


        $conn->prepare($sql);
        $result['data'] = $conn->executeQuery($sql, ['page' => $first])->fetchAllAssociative();
        $result["totalPage"] =  $totalPages;

        return $result;
    }
}
