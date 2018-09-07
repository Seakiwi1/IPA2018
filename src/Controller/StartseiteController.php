<?php

namespace App\Controller;

use App\Entity\Mitarbeiter;
use App\Entity\Seo;
use App\Services\Rechte;
use App\Services\Zeitraum;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use \Datetime;
use App\Form\SeoType;

class StartseiteController extends Controller
{

    /**
     * Rendert die Startseite und übergibt einige Variablen, welche die Resultate
     * der SQL Queries beinhalten, an die View.
     *
     * @Route("/", name="startseite")
     */
    public function index(Request $request, Zeitraum $zeitraumfromto, Rechte $rechte)
    {

        $em = $this->getDoctrine();

        /*
         * Überprüft ob User die benötigten Rechte hat und ob die IP intern oder extern ist.
         * Redirected auf die GAMS Startseite, sofern die Kriterien nicht übereinstimmen
        */
        if (!$rechte->checkUserRechte($em) || !$rechte->checkIP()){
            return $this->redirect('http://192.168.0.35/gams2/asp/welcome.asp');
        }

        $seo = new Seo();

        $fruehstesJahr = new DateTime('2003-01-01');
        $fruehstesJahr = $fruehstesJahr->format('Y');
        $naechstesJahr = date('Y');
        $nYearArray = array();

        for($jahreszahl = $fruehstesJahr; $jahreszahl <= $naechstesJahr; $jahreszahl++){
            $nYearArray[$jahreszahl] = $jahreszahl;
        }

        $alleMitarbeiter = $em->getRepository(Mitarbeiter::class)->findAllMitarbeiter();

        $form = $this->createForm(SeoType::class, $seo);
        $form->handleRequest($request);

        /*
        * Sobald die Form auf der Seite submitted wird, werden
        * die Variablen abgespeichert und dem Zeitraum Service
        * weitergeliefert.
        * Ist die Form nicht submitted werden die Werte auf NULL gesetzt.
        */
        if($form->isSubmitted()) {
            $jahr = $form->get('jahr')->getData();
            $monat = $form->get('monat')->getData();
            $fromto = $zeitraumfromto->getZeitraum($monat, $jahr);
            $from = $fromto[0];
            $to = $fromto[1];
        }else{
            $data = "";
            $from = "";
            $to = "";
            $jahr = "";
        }


        $alleSeos = $em->getRepository(Seo::class)->findAllAnzahlSeo($from, $to, 'seo', $jahr);
        $alleLp = $em->getRepository(Seo::class)->findAllAnzahlLp($from, $to, 'lp', $jahr);


        /*
         * Überrüft, ob die Werte in der Form für SEO Ziele gesetzt wurden oder nicht,
         * falls nicht, werden diese auf NULL gesetzt,
         * Falls die Werte gesetzt wurden, werden sie via POST in variablen abgespeichert und als
         * Query Parameter weiterverwendet
         */
        if (isset($_POST['submitZieleSeo'])){
            if ($_POST['mitarbeiter'] == NULL OR $_POST['jahr'] == NULL OR $_POST['ziel'] == NULL){
                $mitarbeiter = 0;
                $jahr = 0;
                $ziel = 0;
            } else{
                $mitarbeiter = $_POST['mitarbeiter'];
                $jahr = $_POST['jahr'];
                $ziel = $_POST['ziel'];

                $setJahreszieleSeo = $em->getRepository(Seo::class)->setJahresziele($mitarbeiter, $ziel, $jahr, 'seo');
            }

        }

        /*
         * Überrüft, ob die Werte in der Form für Lp Ziele gesetzt wurden oder nicht,
         * falls nicht, werden diese auf NULL gesetzt,
         * Falls die Werte gesetzt wurden, werden sie via POST in variablen abgespeichert und als
         * Query Parameter weiterverwendet
         */
        if (isset($_POST['submitZieleLp'])){
            if ($_POST['mitarbeiter'] == NULL OR $_POST['jahr'] == NULL OR $_POST['ziel'] == NULL){
                $mitarbeiter = 0;
                $jahr = 0;
                $ziel = 0;
            } else{
                $mitarbeiter = $_POST['mitarbeiter'];
                $jahr = $_POST['jahr'];
                $ziel = $_POST['ziel'];

                $setJahreszieleLp = $em->getRepository(Seo::class)->setJahresziele($mitarbeiter, $ziel, $jahr, 'lp');
            }

        }



        return $this->render('startseite/index.html.twig', [
            'alleMitarbeiter' => $alleMitarbeiter,
            'alleSeos' => $alleSeos,
            'alleLp' => $alleLp,
            'anzahlSeos' => $anzahlSeos,
            'jahresziel' => $jahresZiel,
            'monatsziel' => $monatsziel,
            'zeitspanne' => $nYearArray,
            'form' => $form->createView()
            ]);
    }



}
