<?php

namespace App\Repository;

use App\Entity\SwimmingPack;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SwimmingPack|null find($id, $lockMode = null, $lockVersion = null)
 * @method SwimmingPack|null findOneBy(array $criteria, array $orderBy = null)
 * @method SwimmingPack[]    findAll()
 * @method SwimmingPack[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SwimmingPackRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SwimmingPack::class);
    }

//    /**
//     * @return SwimmingPack[] Returns an array of SwimmingPack objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SwimmingPack
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
