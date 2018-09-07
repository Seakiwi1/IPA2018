<?php

namespace App\Repository;

use App\Entity\Jahresziele;
use App\Entity\JahreszieleZs;
use App\Entity\Seo;
use App\Entity\Mitarbeiter;
use App\Entity\SeoTyp;
use App\Entity\UserRolle;
use App\Entity\Rolle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Connection;

/**
 * @method Seo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Seo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Seo[]    findAll()
 * @method Seo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SeoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Seo::class);
    }

    /*
     * Liefert User Vorname, Name, ID, Jahresziel für ein spezifisches Jahr
     * sowie die Anzahl erledigter SEOs für den mitgelieferten Zeitraum, sofern der User
     * zur Technikabteilung gehört und ein Ziel für das angegebene Jahr besitzt, zurück.
     *
     */
     public function findAllAnzahlSeo($from, $to, $ziel, $jahr)
        {
            return $this->createQueryBuilder('seo')
                ->Select('mitarbeiter.id, mitarbeiter.vorname, mitarbeiter.name, jahresziele.ziel, count(seo.id) AS anzahlOptimierung')
                ->innerJoin(Mitarbeiter::class, 'mitarbeiter', 'WITH', 'mitarbeiter.id = seo.mitarbeiter')
                ->innerJoin(SeoTyp::class, 'seoTyp', 'WITH', 'seoTyp.id = seo.seoTyp')
                ->innerJoin(UserRolle::class, 'userRolle', 'WITH', 'mitarbeiter.id = userRolle.mitarbeiter')
                ->innerJoin(Rolle::class, 'rolle', 'WITH', 'rolle.id = userRolle.rolle')
                ->innerJoin(Jahresziele::class, 'jahresziele', 'WITH', 'mitarbeiter.id = jahresziele.mitarbeiter')
                ->Where('seo.optimierung_aufgeschaltet BETWEEN :date_from AND :date_to AND seoTyp.art NOT IN(:seotyp) AND mitarbeiter.name != :standarduser AND mitarbeiter.name != :standarduser2 AND jahresziele.art = :Zielart AND jahresziele.jahr = :jahr')
                ->andWhere('rolle.id = :rolle ')
                ->setParameter('date_to', $to)
                ->setParameter('date_from',$from)
                ->setParameter('rolle', 5)
                ->setParameter('seotyp', array('Zusatzseiten ohne SEO',
                    'Zusatzseiten mit SEO',
                    'Zusatzseiten aufschalten',
                    'Landingpages Template erstellen',
                    'Landingpages aufschalten'), \Doctrine\DBAL\Connection::PARAM_STR_ARRAY)
                ->setParameter('standarduser', 'GLOBO_Friedhof')
                ->setParameter('standarduser2', 'GLOBO_Eingang')
                ->setParameter(':Zielart', $ziel)
                ->setParameter('jahr', $jahr)
                ->GroupBy('mitarbeiter.id, mitarbeiter.vorname, mitarbeiter.name, jahresziele.ziel')
                ->OrderBy('mitarbeiter.vorname', 'ASC')
                ->getQuery()
                ->getResult();
        }

    /*
    * Liefert User Vorname, Name, ID, Jahresziel für ein spezifisches Jahr
    * sowie die Anzahl erledigter LP für den mitgelieferten Zeitraum, sofern der User
    * zur Technikabteilung gehört und ein Ziel für das angegebene Jahr besitzt, zurück.
    *
    */
    public function findAllAnzahlLp($from, $to, $ziel, $jahr)
    {
        return $this->createQueryBuilder('seo')
            ->Select('mitarbeiter.id, mitarbeiter.vorname, mitarbeiter.name, jahresziele.ziel, count(seo.id) AS anzahlLp')
            ->innerJoin(Mitarbeiter::class, 'mitarbeiter', 'WITH', 'mitarbeiter.id = seo.mitarbeiter')
            ->innerJoin(SeoTyp::class, 'seoTyp', 'WITH', 'seoTyp.id = seo.seoTyp')
            ->innerJoin(UserRolle::class, 'userRolle', 'WITH', 'mitarbeiter.id = userRolle.mitarbeiter')
            ->innerJoin(Rolle::class, 'rolle', 'WITH', 'rolle.id = userRolle.rolle')
            ->innerJoin(Jahresziele::class, 'jahresziele', 'WITH', 'mitarbeiter.id = jahresziele.mitarbeiter')
            ->Where('seo.optimierung_aufgeschaltet BETWEEN :date_from AND :date_to AND seoTyp.art IN(:seotyp) AND mitarbeiter.name != :standarduser AND mitarbeiter.name != :standarduser2 AND jahresziele.art = :Zielart AND jahresziele.jahr = :jahr')
            ->andWhere('rolle.id = :rolle ')
            ->setParameter('date_to', $to)
            ->setParameter('date_from',$from)
            ->setParameter('rolle', 5)
            ->setParameter('seotyp', array('Zusatzseiten ohne SEO',
                'Zusatzseiten mit SEO',
                'Zusatzseiten aufschalten',
                'Landingpages Template erstellen',
                'Landingpages aufschalten'), \Doctrine\DBAL\Connection::PARAM_STR_ARRAY)
            ->setParameter('standarduser', 'GLOBO_Friedhof')
            ->setParameter('standarduser2', 'GLOBO_Eingang')
            ->setParameter(':Zielart', 'lp')
            ->setParameter('jahr', $jahr)
            ->GroupBy('mitarbeiter.id, mitarbeiter.vorname, mitarbeiter.name, jahresziele.ziel')
            ->OrderBy('mitarbeiter.vorname', 'ASC')
            ->getQuery()
            ->getResult();
    }






    /*
     * Liefert die User ID, den Vornamen und den Namen sowie die
     * Anzahl der erledigten Zusatzseiten, der erstellten Landingpage Templates
     * sowie der aufgeschalteten Landingpages, im mitgelieferten Zeitraum, sofern der angegebenee User
     * zur Technikabteilung gehört und keiner der Standarduser (bspw. GLOBO_Friedhof) ist.
     */
    public function countAnzahlLpProLpTyp($to, $from){
        $conn = $this->getEntityManager()->getConnection();

        $sql = "Select t_seo.t_user_id, t_user.vorname, t_user.name,
                  sum(case when t_seo_art_ID = 19 then 1 else 0 end) LandingpageTemplate,
                  sum(case when t_seo_art.ID IN(14, 15, 16) then 1 else 0 end) Zusatzseiten,
                  sum(case when t_seo_art_ID = 20 then 1 else 0 end) LandingpageAufschalten
                FROM t_seo 
                INNER JOIN t_user ON t_seo.t_user_id = t_user.ID
                INNER JOIN t_user_rolle ON t_user.id = t_user_rolle.t_user_ID
                INNER JOIN t_rolle ON t_user_rolle.t_rolle_ID = t_rolle.ID
                INNER JOIN t_seo_art ON t_seo.t_seo_art_ID = t_seo_art.ID
                WHERE optimierung_aufgeschaltet BETWEEN ? AND ? AND t_user.id NOT IN (?, ?, ?, ?, ?, ?, ?)  AND t_rolle.id = 5
                GROUP BY t_seo.t_user_id, t_user.vorname, t_user.name
                ORDER BY t_seo.t_user_id ASC
                  ";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $from);
        $stmt->bindValue(2, $to);
        $stmt->bindValue(3, 78);
        $stmt->bindValue(4, 135);
        $stmt->bindValue(5, 304);
        $stmt->bindValue(6, 317);
        $stmt->bindValue(7, 331);
        $stmt->bindValue(8, 335);
        $stmt->bindValue(9, 362);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /*
  * Liefert die User ID, den Vornamen und den Namen sowie die
  * Anzahl der erledigten Seos pro Typ im mitgelieferten Zeitraum, sofern der angegebenee User
  * zur Technikabteilung gehört und keiner der Standarduser (bspw. GLOBO_Friedhof) ist.
  */
    public function countAnzahlSeoProSeoTyp($to, $from){
        $conn = $this->getEntityManager()->getConnection();

        $sql = "Select t_seo.t_user_id, t_user.vorname, t_user.name,
                  sum(case when t_seo_art_ID = 1 then 1 else 0 end) Neukunde,
                  sum(case when t_seo_art.ID = 18 then 1 else 0 end) NeueHomepage,
                  sum(case when t_seo_art_ID = 8 then 1 else 0 end) Ueberschreibung,
                  sum(case when t_seo_art_ID = 11 then 1 else 0 end) KWT,
                  sum(case when t_seo_art_ID = 6 then 1 else 0 end) TeilweiseGut,
                  sum(case when t_seo_art_ID = 2 then 1 else 0 end) AllGood,
                  sum(case when t_seo_art_ID = 17 then 1 else 0 end) SeoAusbauen
                  
                FROM t_seo 
                INNER JOIN t_user ON t_seo.t_user_id = t_user.ID
                INNER JOIN t_user_rolle ON t_user.id = t_user_rolle.t_user_ID
                INNER JOIN t_rolle ON t_user_rolle.t_rolle_ID = t_rolle.ID
                INNER JOIN t_seo_art ON t_seo.t_seo_art_ID = t_seo_art.ID
                WHERE optimierung_aufgeschaltet BETWEEN ? AND ? AND t_user.id NOT IN (?, ?, ?, ?, ?, ?, ?, ?, ?) AND t_rolle.id = 5
                GROUP BY t_seo.t_user_id, t_user.vorname, t_user.name
                ORDER BY t_seo.t_user_id ASC
                  ";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $from);
        $stmt->bindValue(2, $to);
        $stmt->bindValue(3, 78);
        $stmt->bindValue(4, 135);
        $stmt->bindValue(5, 304);
        $stmt->bindValue(6, 317);
        $stmt->bindValue(7, 331);
        $stmt->bindValue(8, 335);
        $stmt->bindValue(9, 362);
        $stmt->bindValue(10, 251);
        $stmt->bindValue(11, 333);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /*
     * Erstellt eine neue Row in der table t_user_jahresziele, sofern
     * für den mitgelieferten User im mitgelieferten Jahr für die
     * mitgelieferte Optimierungart (seo oder lp), noch kein Datensatz existiert.
     * Falls ein Datensatz mit diesen Attributen bereits existiert, wird dieser
     * mit den neuen Werten überschrieben.
     */
    public function setJahresziele($mitarbeiter, $ziel, $jahr, $art){
        $conn = $this->getEntityManager()->getConnection();

        $sql = "IF EXISTS (SELECT * FROM t_user_jahresziele WHERE t_user_id = ? AND art = ? AND jahr = ?)
                    BEGIN
                        Update t_user_jahresziele
                        SET ziel = ?
                        WHERE t_user_id = ? AND art = ? AND jahr = ?
                    END
                ELSE
                    BEGIN
                        INSERT INTO t_user_jahresziele
                        VALUES(?, ?, ?, ?)
                    END";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $mitarbeiter);
        $stmt->bindValue(2, $art);
        $stmt->bindValue(3, $jahr);
        $stmt->bindValue(4, $ziel);
        $stmt->bindValue(5, $mitarbeiter);
        $stmt->bindValue(6, $art);
        $stmt->bindValue(7, $jahr);
        $stmt->bindValue(8, $mitarbeiter);
        $stmt->bindValue(9, $art);
        $stmt->bindValue(10, $jahr);
        $stmt->bindValue(11, $ziel);
        $stmt->execute();

        return $stmt->fetchAll();
    }


    /*
     * Liefert alle SEO Optimierungsarten
     * für den mitgegebenen User im mitgegebenen Zeitraum
     * zurück.
     */
    public function getOptimierungArten($to, $from, $id){
        $conn = $this->getEntityManager()->getConnection();

        $sql = "Select t_seo_art.art
                FROM t_seo 
                INNER JOIN t_user ON t_seo.t_user_id = t_user.ID
                INNER JOIN t_user_rolle ON t_user.id = t_user_rolle.t_user_ID
                INNER JOIN t_rolle ON t_user_rolle.t_rolle_ID = t_rolle.ID
                INNER JOIN t_seo_art ON t_seo.t_seo_art_ID = t_seo_art.ID
                WHERE optimierung_aufgeschaltet BETWEEN ? AND ? AND t_user.id = ? AND t_seo_art.art NOT IN (?, ?, ?, ?, ?, ?)
                GROUP BY t_seo.t_user_id, t_user.vorname, t_user.name, t_seo_art.art
                ORDER BY t_seo.t_user_id ASC
                  ";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $from);
        $stmt->bindValue(2, $to);
        $stmt->bindValue(3, $id);
        $stmt->bindValue(4, 'Zusatzseiten ohne SEO');
        $stmt->bindValue(5,'Zusatzseiten mit SEO');
        $stmt->bindValue(6,'Zusatzseiten aufschalten');
        $stmt->bindValue(7,'Landingpages Template erstellen');
        $stmt->bindValue(8,'Landingpages aufschalten');
        $stmt->bindValue(9, 'Statistik korrigieren');
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /*
     * Liefert die Anzahl der erledigten Aufträge der
     * mitgegebenen Auftragsart, welche der mitgegebene User im
     * mitgegebenen Zeitraum abgeschlossen hat, zurück.
     */
    public function getAnzahlProMitarbeiter($id, $from, $to, $art){
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT  count(t_seo.id)
                FROM t_seo
                INNER JOIN t_seo_art ON t_seo.t_seo_art_ID = t_seo_art.ID
                WHERE t_seo.t_user_id = ? AND t_seo_art.art = ? AND t_seo.optimierung_aufgeschaltet between ? AND ?
                  ";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->bindValue(2, $art);
        $stmt->bindValue(3, $from);
        $stmt->bindValue(4, $to);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /*
     * Liefert die Anzahl der erledigten SEOs des mitgegebenen Users
     * im mitgegebenen Zeitraum, zurück.
     */
    public function getAnzahlSeosProMitarbeiter($id, $from, $to){
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT  count(t_seo.id)
                FROM t_seo
                INNER JOIN t_seo_art ON t_seo.t_seo_art_ID = t_seo_art.ID
                WHERE t_seo.t_user_id = ? AND t_seo_art.art IN (?, ?, ?, ?, ?, ?, ?) AND t_seo.optimierung_aufgeschaltet between ? AND ?
                  ";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->bindValue(2, 'Überschreibung');
        $stmt->bindValue(3, 'Allgood');
        $stmt->bindValue(4, 'Keywords austauschen');
        $stmt->bindValue(5, 'Neue Homepage');
        $stmt->bindValue(6, 'Neukunde');
        $stmt->bindValue(7, 'SEO ausbauen');
        $stmt->bindValue(8, 'Teilweise Gut');
        $stmt->bindValue(9, $from);
        $stmt->bindValue(10, $to);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /*
     * Liefert die Anzahl der erledigten Lp des mitgegebenen Users
     * im mitgegebenen Zeitraum, zurück.
     */
    public function getAnzahlAlleZsProMitarbeiter($id, $from, $to){
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT  count(t_seo.id)
                FROM t_seo
                INNER JOIN t_seo_art ON t_seo.t_seo_art_ID = t_seo_art.ID
                WHERE t_seo.t_user_id = ? AND t_seo_art.art IN (?, ?, ?, ?, ?) AND t_seo.optimierung_aufgeschaltet between ? AND ?
                  ";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->bindValue(2, 'Zusatzseiten ohne SEO');
        $stmt->bindValue(3, 'Zusatzseiten mit SEO');
        $stmt->bindValue(4, 'Zusatzseiten aufschalten');
        $stmt->bindValue(5, 'Landingpages Template erstellen');
        $stmt->bindValue(6, 'Landingpages aufschalten');
        $stmt->bindValue(7, $from);
        $stmt->bindValue(8, $to);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /*
     * Liefert die Anzahl der erledigten Zusatzseiten des mitgegebenen Users
     * im mitgegebenen Zeitraum, zurück.
     */

    public function getAnzahlZusatzseitenProMitarbeiter($id, $from, $to){
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT  count(t_seo.id) 
                FROM t_seo
                INNER JOIN t_seo_art ON t_seo.t_seo_art_ID = t_seo_art.ID
                WHERE t_seo.t_user_id = ? AND t_seo_art.art IN (?, ?, ?) AND t_seo.optimierung_aufgeschaltet between ? AND ?
                  ";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->bindValue(2, 'Zusatzseiten ohne SEO');
        $stmt->bindValue(3, 'Zusatzseiten mit SEO');
        $stmt->bindValue(4, 'Zusatzseiten aufschalten');
        $stmt->bindValue(5, $from);
        $stmt->bindValue(6, $to);
        $stmt->execute();

        return $stmt->fetchAll();
    }


//    /**
//     * @return Seo[] Returns an array of Seo objects
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
    public function findOneBySomeField($value): ?Seo
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
