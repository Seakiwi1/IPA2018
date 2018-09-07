<?php

namespace App\Controller;

use App\Services\Zeitraum;
use App\Services\Rechte;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use \Datetime;
use App\Entity\Seo;
use App\Form\SeoType;
use App\Entity\Mitarbeiter;
use Symfony\Component\HttpFoundation\JsonResponse;

class DetailansichtController extends Controller
{
    /**
     * Rendert die Detailansicht eines jeden Mitarbeiters und
     * gibt Variablen zum Verwenden im Template mit. Die Variablen
     * werden durch SQL Query Aufrufe übergeben.
     * @Route("/detailansicht", name="detailansicht")
     */
    public function index(Request $request, Zeitraum $zeitraumfromto, Rechte $rechte)
    {

        $em = $this->getDoctrine();
        $emSeo = $this->getDoctrine()->getRepository(Seo::class);

        /*
         * Überprüft ob User die benötigten Rechte hat und ob die IP intern oder extern ist.
         * Redirected auf die GAMS Startseite, sofern die Kriterien nicht übereinstimmen
        */
        if (!$rechte->checkUserRechte($em) || !$rechte->checkIP()) {
            return $this->redirect('http://192.168.0.35/gams2/asp/welcome.asp');
        }

        $seo = new Seo();
        $form = $this->createForm(SeoType::class, $seo);

        $form->handleRequest($request);

        /*
         * Sobald die Form auf der Seite submitted wird, werden
         * die Variablen abgespeichert und dem Zeitraum Service
         * weitergeliefert.
         * Ist die Form nicht submitted werden die Werte auf NULL gesetzt.
         */
        if ($form->isSubmitted()) {
            $jahr = $form->get('jahr')->getData();
            $monat = $form->get('monat')->getData();
            $fromto = $zeitraumfromto->getZeitraum($monat, $jahr);
            $from = $fromto[0];
            $to = $fromto[1];
        } else {
            $from = "";
            $to = "";
            $jahr = "";
        }


        /*
         * Bezieht die ID, welche mit der URL mitgeliefert wird
         * und casted diese von einem String zu einem Integer,
         * um die Variable direkt als Query Parameter weitergeben
         * zu können
         */
        $id = $_GET['mitarbeiter'];
        $id = intval($id);

        $einzelnerMitarbeiter = $em->getRepository(Mitarbeiter::class)->findSpecificMitarbeiter($id);
        $alleMitarbeiter = $em->getRepository(Mitarbeiter::class)->findAllMitarbeiter();
        $alleSeos = $emSeo->findAllAnzahlSeo($from, $to, 'seo', $jahr);
        $SeoProMitarbeiter = $emSeo->getOptimierungArten($to, $from, $id);

        /*
         * Bezieht die Anzahl der verschiedenen Arten von SEOs bzw. LP, speichert den
         * zurückgelieferten Wert ab und wandelt den zurückgelieferten String zu einem
         * Integer um.
         */

        $getAnzahlAllGoodProMitarbeiter = $emSeo->getAnzahlProMitarbeiter($id, $from, $to, 'Allgood');
        $getAnzahlAllGoodProMitarbeiter = $getAnzahlAllGoodProMitarbeiter[0];
        $getAnzahlAllGoodProMitarbeiter = implode('', $getAnzahlAllGoodProMitarbeiter);

        $getAnzahlKWTProMitarbeiter = $emSeo->getAnzahlProMitarbeiter($id, $from, $to, 'Keywords austauschen');
        $getAnzahlKWTProMitarbeiter = $getAnzahlKWTProMitarbeiter[0];
        $getAnzahlKWTProMitarbeiter = implode('', $getAnzahlKWTProMitarbeiter);

        $getAnzahlNeueHomepageMitarbeiter = $emSeo->getAnzahlProMitarbeiter($id, $from, $to, 'Neue Homepage');
        $getAnzahlNeueHomepageMitarbeiter = $getAnzahlNeueHomepageMitarbeiter[0];
        $getAnzahlNeueHomepageMitarbeiter = implode('', $getAnzahlNeueHomepageMitarbeiter);

        $getAnzahlNeukundeProMitarbeiter = $emSeo->getAnzahlProMitarbeiter($id, $from, $to, 'Neukunde');
        $getAnzahlNeukundeProMitarbeiter = $getAnzahlNeukundeProMitarbeiter[0];
        $getAnzahlNeukundeProMitarbeiter = implode('', $getAnzahlNeukundeProMitarbeiter);

        $getAnzahlSeoAusbauenProMitarbeiter = $emSeo->getAnzahlProMitarbeiter($id, $from, $to, 'Seo ausbauen');
        $getAnzahlSeoAusbauenProMitarbeiter = $getAnzahlSeoAusbauenProMitarbeiter[0];
        $getAnzahlSeoAusbauenProMitarbeiter = implode('', $getAnzahlSeoAusbauenProMitarbeiter);


        $getAnzahlTeilweiseGutProMitarbeiter = $emSeo->getAnzahlProMitarbeiter($id, $from, $to, 'Teilweise Gut');
        $getAnzahlTeilweiseGutProMitarbeiter = $getAnzahlTeilweiseGutProMitarbeiter[0];
        $getAnzahlTeilweiseGutProMitarbeiter = implode('', $getAnzahlTeilweiseGutProMitarbeiter);

        $getAnzahlUeberschreibungProMitarbeiter = $emSeo->getAnzahlProMitarbeiter($id, $from, $to, 'Überschreibung');
        $getAnzahlUeberschreibungProMitarbeiter = $getAnzahlUeberschreibungProMitarbeiter[0];
        $getAnzahlUeberschreibungProMitarbeiter = implode('', $getAnzahlUeberschreibungProMitarbeiter);

        $getAnzahlSeosProMitarbeiter = $emSeo->getAnzahlSeosProMitarbeiter($id, $from, $to);
        $getAnzahlSeosProMitarbeiter = $getAnzahlSeosProMitarbeiter[0];
        $getAnzahlSeosProMitarbeiter = implode('', $getAnzahlSeosProMitarbeiter);

        $getAnzahlZsProMitarbeiter = $emSeo->getAnzahlAlleZsProMitarbeiter($id, $from, $to);
        $getAnzahlZsProMitarbeiter = $getAnzahlZsProMitarbeiter[0];
        $getAnzahlZsProMitarbeiter = implode('', $getAnzahlZsProMitarbeiter);

        $getAnzahlLpTemplateProMitarbeiter = $emSeo->getAnzahlProMitarbeiter($id, $from, $to, 'Landingpages Template erstellen');
        $getAnzahlLpTemplateProMitarbeiter = $getAnzahlLpTemplateProMitarbeiter[0];
        $getAnzahlLpTemplateProMitarbeiter = implode('', $getAnzahlLpTemplateProMitarbeiter);

        $getAnzahlLpAufgeschaltetProMitarbeiter = $emSeo->getAnzahlProMitarbeiter($id, $from, $to, 'Landingpages aufschalten');
        $getAnzahlLpAufgeschaltetProMitarbeiter = $getAnzahlLpAufgeschaltetProMitarbeiter[0];
        $getAnzahlLpAufgeschaltetProMitarbeiter = implode('', $getAnzahlLpAufgeschaltetProMitarbeiter);

        $getAnzahlZusatzseitenProMitarbeiter = $emSeo->getAnzahlZusatzseitenProMitarbeiter($id, $from, $to);
        $getAnzahlZusatzseitenProMitarbeiter = $getAnzahlZusatzseitenProMitarbeiter[0];
        $getAnzahlZusatzseitenProMitarbeiter = implode('', $getAnzahlZusatzseitenProMitarbeiter);


        $alleLp = $this->getDoctrine()->getRepository(Seo::class)->findAllAnzahlLp($from, $to, 'seo', $jahr);


        return $this->render('detailansicht/index.html.twig', [
            'form' => $form->createView(),
            'mitarbeiter' => $alleMitarbeiter,
            'ausgewaehlterMitarbeiter' => $einzelnerMitarbeiter,
            'optimierungProMitarbeiter' => $SeoProMitarbeiter,
            'AllGoodProMitarbeiter' => $getAnzahlAllGoodProMitarbeiter,
            'KwtProMitarbeiter' => $getAnzahlKWTProMitarbeiter,
            'NeueHomepageProMitarbeiter' => $getAnzahlNeueHomepageMitarbeiter,
            'SeoAusbauenProMitarbeiter' => $getAnzahlSeoAusbauenProMitarbeiter,
            'NeukundeProMitarbeiter' => $getAnzahlNeukundeProMitarbeiter,
            'TeilweiseGutProMitarbeiter' => $getAnzahlTeilweiseGutProMitarbeiter,
            'UeberschreibungProMitarbeiter' => $getAnzahlUeberschreibungProMitarbeiter,
            'LpTemplateProMitarbeiter' => $getAnzahlLpTemplateProMitarbeiter,
            'LpAufgeschaltetProMitarbeiter' => $getAnzahlLpAufgeschaltetProMitarbeiter,
            'ZusatzseitenProMitarbeiter' => $getAnzahlZusatzseitenProMitarbeiter,
            'SeoProMitarbeiter' => $getAnzahlSeosProMitarbeiter,
            'ZsProMitarbeiter' => $getAnzahlZsProMitarbeiter,
            'alleSeos' => $alleSeos,
            'alleLp' => $alleLp,
        ]);
    }

    /**
     * Wandelt die Anzahl Seos und LP pro Mitarbeiter in einen Array um
     * und sendet diesen als JSSON an ein generiertes JSON File.
     * Das JSON File ist nach folgendem Schema aufgebaut:
     * /mitarbeiter/{jahr}/{monat}
     *
     * @Route("/detailansicht/mitarbeiter/{jahr}/{monat}")
     */
    public function returnJson($jahr, $monat, Zeitraum $zeitraumfromto)
    {

        $fromto = $zeitraumfromto->getZeitraum($monat, $jahr);
        $from = $fromto[0];
        $to = $fromto[1];
        $alleSeo = $this->getDoctrine()->getRepository(Seo::class)->findAllAnzahlSeo($from, $to, 'seo', $jahr);
        $alleLp = $this->getDoctrine()->getRepository(Seo::class)->findAllAnzahlLp($from, $to, 'lp', $jahr);
        $seoProMitarbeiter = array();
        $seoProMitarbeiter[0] = array_column($alleSeo, 'anzahlOptimierung');
        $seo1 = array_column($alleSeo, 'vorname');
        $seo2 = array_column($alleSeo, 'name');
        $seo = array_combine($seo1, $seo2);
        $seo = array_map(function ($k, $v) {
            return "$k $v";
        }, array_keys($seo), array_values($seo));
        $seoProMitarbeiter[1] = $seo;
        $seoProMitarbeiter[2] = array_column($alleLp, 'anzahlLp');


        return new JsonResponse($seoProMitarbeiter);
    }

}
