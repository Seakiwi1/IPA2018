<?php

namespace App\Repository;

use App\Entity\RechteGams;
use App\Entity\Mitarbeiter;
use App\Entity\UserRechte;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method RechteGams|null find($id, $lockMode = null, $lockVersion = null)
 * @method RechteGams|null findOneBy(array $criteria, array $orderBy = null)
 * @method RechteGams[]    findAll()
 * @method RechteGams[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RechteGamsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RechteGams::class);
    }


    /*
     * Überprüft, ob der User die Berechtigung besitzt und liefert beide zurück
     */
    public function checkRechte($currentUser, $berechtigung){
        return $this->getEntityManager()->getRepository(RechteGams::class)->createQueryBuilder('rechteGams')
            ->Select ('rechteGams.id, mitarbeiter.id')
            ->innerJoin(UserRechte::class, 'userRechte', 'WITH', 'rechteGams.id = userRechte.rechteGams')
            ->innerJoin(Mitarbeiter::class, 'mitarbeiter', 'WITH', 'mitarbeiter.id = userRechte.mitarbeiter')
            ->where('mitarbeiter.id = :currentUser AND rechteGams.id = :berechtigung')
            ->setParameter('currentUser', $currentUser)
            ->setParameter('berechtigung', $berechtigung)
            ->getQuery()
            ->getResult();
    }
//    /**
//     * @return RechteGams[] Returns an array of RechteGams objects
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
    public function findOneBySomeField($value): ?RechteGams
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
