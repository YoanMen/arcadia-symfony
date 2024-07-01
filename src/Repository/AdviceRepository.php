<?php

namespace App\Repository;

use App\Entity\Advice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Advice>
 */
class AdviceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Advice::class);
    }

    /**
     * @return Paginator<Advice>
     */
    public function paginateApprovedAdvice(int $page): Paginator
    {
        return new Paginator($this->createQueryBuilder('r')
            ->where('r.approved = true')
            ->setFirstResult(($page - 1) * 1)
            ->orderBy('r.id', 'DESC')
            ->setMaxResults(1));
    }
}
