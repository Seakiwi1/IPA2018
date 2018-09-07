<?php

namespace App\Repository;

use App\Entity\Jahresziele;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Jahresziele|null find($id, $lockMode = null, $lockVersion = null)
 * @method Jahresziele|null findOneBy(array $criteria, array $orderBy = null)
 * @method Jahresziele[]    findAll()
 * @method Jahresziele[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JahreszieleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Jahresziele::class);
    }

//    /**
//     * @return Jahresziele[] Returns an array of Jahresziele objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Jahresziele
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
