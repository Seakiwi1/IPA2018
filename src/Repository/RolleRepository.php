<?php

namespace App\Repository;

use App\Entity\Rolle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Rolle|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rolle|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rolle[]    findAll()
 * @method Rolle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RolleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Rolle::class);
    }

//    /**
//     * @return Rolle[] Returns an array of Rolle objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Rolle
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
