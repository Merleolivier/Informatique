<?php

namespace App\Repository;

use App\Entity\Reparations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Reparations|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reparations|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reparations[]    findAll()
 * @method Reparations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReparationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reparations::class);
    }

    // /**
    //  * @return Reparations[] Returns an array of Reparations objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Reparations
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
