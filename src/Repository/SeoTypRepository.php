<?php

namespace App\Repository;

use App\Entity\Mitarbeiter;
use App\Entity\SeoTyp;
use App\Entity\Url;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SeoTyp|null find($id, $lockMode = null, $lockVersion = null)
 * @method SeoTyp|null findOneBy(array $criteria, array $orderBy = null)
 * @method SeoTyp[]    findAll()
 * @method SeoTyp[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SeoTypRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SeoTyp::class);
    }

//    /**
//     * @return SeoTyp[] Returns an array of SeoTyp objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SeoTyp
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
