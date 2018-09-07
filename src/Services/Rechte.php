<?php
/**
 * Created by PhpStorm.
 * User: Dennis Skaletz
 * Date: 4/17/2018
 * Time: 4:11 PM
 */

namespace App\Services;


use App\Entity\Mitarbeiter;
use App\Entity\RechteGams;

class Rechte
{

    private $activeUser;

    public function __construct()
    {
        $this->activeUser = Mitarbeiter::ACTIVE_USER;
    }

    /*
     * Überprüft, ob die die UserID eine Zuweisung zur BerechtigungsID besitzt.
     *
     * @return bool
     */
    public function checkUserRechte($em){
        $userRecht = $em->getRepository(RechteGams::class)->checkRechte($this->activeUser, 156);

        return $userRecht ? true : false;
    }

    /*
     * Überprüft, ob IP lokal ist. Muss beim Aufschalten ins Gams auf 192.168. abgeändert werden.
     *
     * @return bool
     */
    public function checkIP(){
        if(strpos($_SERVER['REMOTE_ADDR'], '127.0') !== false){
            return true;
        }else{
            return false;
        }
    }
}