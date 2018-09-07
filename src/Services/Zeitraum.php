<?php
/**
 * Created by PhpStorm.
 * User: Dennis Skaletz
 * Date: 4/17/2018
 * Time: 9:15 AM
 */

namespace App\Services;
use \Datetime;

class Zeitraum
{
    /*
     * Wandelt die Parameter in Variablen in gültigem DateTime Format um.
     * Falls der Value für Moant 13 (Ganzes Jahr) ist, so wird
     * das ganze Jahr als Zeitraum gesetzt.
     *
     * @return array
     */
    public function getZeitraum($monat, $jahr)
    {
        if($monat == 13){
            $from = $jahr . '-' . '01' . '-01';
            $to = $jahr . '-' . '12' . '-31';
            $from = new Datetime($from);
            $from = $from->format('Y-d-m');
            $to = new Datetime($to);
            $to = $to->format('Y-d-m');
        }else{
            $from = $jahr . '-' . $monat . '-01';
            $to = $jahr . '-' . $monat . '-31';
            $from = new Datetime($from);
            $from = $from->format('Y-d-m');
            $to = new Datetime($to);
            $to = $to->format('Y-d-m');
        }

        $arrayZeitraum = [$from, $to];
        return $arrayZeitraum;
    }

}