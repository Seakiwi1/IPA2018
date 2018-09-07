<?php

namespace App\Repository;

use App\Entity\UserRolle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserRolle|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserRolle|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserRolle[]    findAll()
 * @method UserRolle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRolleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserRolle::class);
    }

//    /**
//     * @return UserRolle[] Returns an array of UserRolle objects
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
    public function findOneBySomeField($value): ?UserRolle
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
