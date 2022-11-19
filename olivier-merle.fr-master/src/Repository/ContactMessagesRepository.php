<?php

namespace App\Repository;

use App\Entity\ContactMessages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ContactMessages|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContactMessages|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContactMessages[]    findAll()
 * @method ContactMessages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactMessagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContactMessages::class);
    }

    // /**
    //  * @return ContactMessages[] Returns an array of ContactMessages objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ContactMessages
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
