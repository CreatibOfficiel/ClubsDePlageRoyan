<?php

namespace App\Repository;

use App\Entity\Educator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Educator|null find($id, $lockMode = null, $lockVersion = null)
 * @method Educator|null findOneBy(array $criteria, array $orderBy = null)
 * @method Educator[]    findAll()
 * @method Educator[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EducatorRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Educator::class);
    }

//    /**
//     * @return Educator[] Returns an array of Educator objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Educator
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
