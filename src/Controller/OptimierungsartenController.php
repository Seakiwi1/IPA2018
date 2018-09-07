<?php

namespace App\Controller;

use App\Services\Rechte;
use App\Services\Zeitraum;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use \Datetime;
use App\Form\SeoType;
use App\Entity\Seo;
use App\Entity\Mitarbeiter;

class OptimierungsartenController extends Controller
{
    /**
     *
     * Rendert die Optimierungsarten Seite und übergibt einige Variablen, welche die Resultate
     * der SQL Queries beinhalten, an die View.
     *
     * @Route("/optimierungsarten", name="optimierungsarten")
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
        }

        $countAnzahlLpProLpTyp = $em->getRepository(Seo::class)->countAnzahlLpProLpTyp($to, $from);
        $countAnzahlSeoProSeoTyp = $em->getRepository(Seo::class)->countAnzahlSeoProSeoTyp($to, $from);


        return $this->render('optimierungsarten/index.html.twig', [
            'controller_name' => 'OptimierungsartenController',
            'LpProTyp' => $countAnzahlLpProLpTyp,
            'SeoProTyp' => $countAnzahlSeoProSeoTyp,
            'form' => $form->createView(),
        ]);
    }
}
