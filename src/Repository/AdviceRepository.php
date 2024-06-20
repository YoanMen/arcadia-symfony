<?php

namespace App\Repository;

use App\Entity\Advice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;


/**
 * @extends ServiceEntityRepository<Advice>
 */
class AdviceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Advice::class);
    }


    public function paginateApprovedAdvice(int $page): Paginator
    {

        return new Paginator($this->createQueryBuilder('r')
            ->where('r.approved = true')
            ->setFirstResult(($page - 1) * 1)
            ->orderBy('r.id', 'DESC')
            ->setMaxResults(1));
    }
}
