<?php

namespace App\Repository;

use App\Entity\BookingLesson;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BookingLesson|null find($id, $lockMode = null, $lockVersion = null)
 * @method BookingLesson|null findOneBy(array $criteria, array $orderBy = null)
 * @method BookingLesson[]    findAll()
 * @method BookingLesson[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookingLessonRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BookingLesson::class);
    }

//    /**
//     * @return BookingLesson[] Returns an array of BookingLesson objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?BookingLesson
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
