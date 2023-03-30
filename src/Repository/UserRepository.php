<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findByMail(?string $mail): ?User
    {
        return $this->findOneBy(['mail' => $mail]);
    }

//$er->createQueryBuilder('u')
//->join('u.educator', 'e')
//->where("u.roles LIKE 'ROLE_EDUCATOR'")
//->andWhere("e.club_id LIKE :club_id")
//->setParameters([
//'club_id' => $options['club'],
//])

//get user with role educator and club_id = $id

    public function findByEducatorByClub($clubId)
    {
        $qb = $this->createQueryBuilder('u');
        $qb->join('u.educator', 'e')
            ->where("u.roles LIKE 'ROLE_EDUCATOR'")
            ->andWhere("e.club_id = :clubId")
            ->setParameters([
                'clubId' => $clubId,
            ]);
        return $qb;
    }


//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
