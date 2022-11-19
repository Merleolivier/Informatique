<?php

namespace App\Repository;

use App\Entity\TicketsResponse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TicketsResponse|null find($id, $lockMode = null, $lockVersion = null)
 * @method TicketsResponse|null findOneBy(array $criteria, array $orderBy = null)
 * @method TicketsResponse[]    findAll()
 * @method TicketsResponse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TicketsResponseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TicketsResponse::class);
    }

    // /**
    //  * @return TicketsResponse[] Returns an array of TicketsResponse objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TicketsResponse
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
