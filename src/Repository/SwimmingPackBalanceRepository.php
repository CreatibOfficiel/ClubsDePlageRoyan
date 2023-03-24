<?php

namespace App\Repository;

use App\Entity\SwimmingPackBalance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SwimmingPackBalance>
 *
 * @method SwimmingPackBalance|null find($id, $lockMode = null, $lockVersion = null)
 * @method SwimmingPackBalance|null findOneBy(array $criteria, array $orderBy = null)
 * @method SwimmingPackBalance[]    findAll()
 * @method SwimmingPackBalance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SwimmingPackBalanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SwimmingPackBalance::class);
    }

    public function save(SwimmingPackBalance $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SwimmingPackBalance $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
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
