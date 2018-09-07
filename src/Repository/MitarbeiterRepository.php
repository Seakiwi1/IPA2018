<?php

namespace App\Repository;

use App\Entity\UserRolle;
use App\Entity\Rolle;
use App\Entity\Mitarbeiter;
use App\Entity\Seo;
use App\Entity\SeoTyp;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Mitarbeiter|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mitarbeiter|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mitarbeiter[]    findAll()
 * @method Mitarbeiter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MitarbeiterRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Mitarbeiter::class);
    }

    /*
     * Liefert ID, Vorname und Name von Mitarbeiter mit mitgegebener ID zurück.
     */
    public function findSpecificMitarbeiter($id){
        return $this->getEntityManager()->getRepository(Mitarbeiter::class)->createQueryBuilder('mitarbeiter')
            ->select('mitarbeiter.id, mitarbeiter.vorname, mitarbeiter.name')
            ->where('mitarbeiter.id = :ausgewaehlterMitarbeiter')
            ->setParameter('ausgewaehlterMitarbeiter', $id)
            ->getQuery()
            ->getResult();
    }

    /*
     * Liefert alle aktiven Mitarbeiter der Abteilung "Technik" zurück.
     * Einige "Standarduser" werden dafür gefiltert.
     */
    public function findAllMitarbeiter()
    {
        return $this->getEntityManager()->getRepository(Mitarbeiter::class)->createQueryBuilder('mitarbeiter')
            ->select('mitarbeiter.name, mitarbeiter.vorname', 'mitarbeiter.id')
            ->innerJoin(UserRolle::class, 'userRolle', 'WITH', 'mitarbeiter.id = userRolle.mitarbeiter')
            ->innerJoin(Rolle::class, 'rolle', 'WITH', 'rolle.id = userRolle.rolle')
            ->Where('mitarbeiter.status = :status AND rolle.id = :rolle 
                               AND mitarbeiter.name != :mustermann 
                               AND mitarbeiter.name != :globo 
                               AND mitarbeiter.name != :techniker 
                               AND mitarbeiter.name != :eingang 
                               AND mitarbeiter.name != :globoUnvollstaendig 
                               AND mitarbeiter.name != :friedhof')
            ->setParameter('status', 1)
            ->setParameter('rolle', 5)
            ->setParameter('mustermann', "mustermann")
            ->setParameter('globo', "globonet")
            ->setParameter('techniker', "techniker")
            ->setParameter('eingang', "GLOBO_Eingang")
            ->setParameter('globoUnvollstaendig', "GLOBO_unvollständig")
            ->setParameter('friedhof', "GLOBO_Friedhof")
            ->getQuery()
            ->getResult();
    }

    /* @todo löschen falls seite läuft
     *  wenn Seite läuft obwohl die Query kommentiert ist -> löschen
    public function findAllSeoType($from, $to){
        return $this->createQueryBuilder('mitarbeiter')
            ->Select('mitarbeiter.id, mitarbeiter.vorname, mitarbeiter.name, sum(case when seo.t_seo_art_id = 14 then 1 ELSE 0 END) AS zusatzseiten, sum(case when seo.t_seo_art_id = 19 then 1 ELSE 0 END) AS landingpageTemplate')
            ->innerJoin(Seo::class, 'seo', 'WITH', 'seo.mitarbeiter = mitarbeiter.id')
            ->innerJoin(SeoTyp::class, 'seoTyp', 'WITH', 'seoTyp.id = seo.seoTyp')
            ->Where('seo.optimierung_aufgeschaltet BETWEEN :date_from AND :date_to AND mitarbeiter.name != :standarduser AND mitarbeiter.name != :standarduser2 ')
            ->setParameter('date_to', $to)
            ->setParameter('date_from',$from)
            ->setParameter('standarduser', 'GLOBO_Friedhof')
            ->setParameter('standarduser2', 'GLOBO_Eingang')
            ->GroupBy('mitarbeiter.id, mitarbeiter.vorname, mitarbeiter.name, seo.t_seo_art_id')
            ->OrderBy('mitarbeiter.vorname', 'ASC')
            ->getQuery()
            ->getResult();
    }
    */


//    /**
//     * @return Mitarbeiter[] Returns an array of Mitarbeiter objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Mitarbeiter
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
