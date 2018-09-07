<?php
/**
 * Created by PhpStorm.
 * User: Dennis Skaletz
 * Date: 4/12/2018
 * Time: 11:18 AM
 */

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormInterface;
use DateTime;

class SeoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        /*
         * Setzt das frühste Datum, welches im GAMS zu finden ist.
         * Das Format ist lediglich die Jahreszahl.
         */
        $fruehstesJahr = new DateTime('2003-01-01');
        $fruehstesJahr = $fruehstesJahr->format('Y');
        $naechstesJahr = date('Y');
        $nYearArray = array();

        /*
         * Läuft durch die Schlaufe und generiert einen Array Eintrag für jedes Jahr zwischen
         * dem ältesten und aktuellem Jahr.
         */
        for($jahreszahl = $fruehstesJahr; $jahreszahl <= $naechstesJahr; $jahreszahl++){
            $nYearArray[$jahreszahl] = $jahreszahl;
        }

        $builder
            ->setMethod('POST')
            ->add('jahr', ChoiceType::class,
                array(
                    'choices' => $nYearArray,
                    'mapped' => false,
                    'data' => '2017'
                )
            )
            ->add('monat', ChoiceType::class,
                array(
                    'choices' => array(
                        'Januar' => 01,
                        'Februar' => 02,
                        'März' => 03,
                        'April' => 04,
                        'Mai' => 05,
                        'Juni' => 06,
                        'Juli' => 07,
                        'August' => '08',
                        'September' => '09',
                        'Oktober' => 10,
                        'November' => 11,
                        'Dezember' => 12,
                        'Ganzes Jahr' => 13
                    ),
                    'mapped' => false,
                    'data' => 13
                )
                )
            ->add('Zeitraum anwenden', SubmitType::class)
            ;
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'allow_extra_fields' => true
        ));
    }
}