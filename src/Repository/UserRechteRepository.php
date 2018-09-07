<?php

namespace App\Repository;

use App\Entity\UserRechte;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserRechte|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserRechte|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserRechte[]    findAll()
 * @method UserRechte[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRechteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserRechte::class);
    }

//    /**
//     * @return UserRechte[] Returns an array of UserRechte objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserRechte
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
