<?php

namespace App\Repository;

use App\Entity\Sclass;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sclass|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sclass|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sclass[]    findAll()
 * @method Sclass[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SclassRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sclass::class);
    }

    // /**
    //  * @return Sclass[] Returns an array of Sclass objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sclass
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
