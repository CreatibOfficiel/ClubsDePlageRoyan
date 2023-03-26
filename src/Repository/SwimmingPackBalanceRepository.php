<?php

namespace App\Repository;

use App\Entity\SwimmingPackBalance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SwimmingPackBalance|null find($id, $lockMode = null, $lockVersion = null)
 * @method SwimmingPackBalance|null findOneBy(array $criteria, array $orderBy = null)
 * @method SwimmingPackBalance[]    findAll()
 * @method SwimmingPackBalance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SwimmingPackBalanceRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SwimmingPackBalance::class);
    }

//    /**
//     * @return SwimmingPackBalance[] Returns an array of SwimmingPackBalance objects
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

//    public function findOneBySomeField($value): ?SwimmingPackBalance
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
