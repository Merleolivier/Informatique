<?php

namespace App\Repository;

use App\Entity\AvisClients;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AvisClients|null find($id, $lockMode = null, $lockVersion = null)
 * @method AvisClients|null findOneBy(array $criteria, array $orderBy = null)
 * @method AvisClients[]    findAll()
 * @method AvisClients[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AvisClientsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AvisClients::class);
    }

    // /**
    //  * @return AvisClients[] Returns an array of AvisClients objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AvisClients
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
