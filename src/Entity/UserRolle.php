<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRolleRepository")
 * @ORM\Table(name="t_user_rolle")
 */
class UserRolle
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $t_user_ID;

    /**
     * @ORM\Column(type="integer")
     */
    private $t_rolle_ID;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Mitarbeiter", inversedBy="userRolle")
     * @ORM\JoinColumn(name="t_user_id", referencedColumnName="id")
     */
    private $mitarbeiter;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Rolle", inversedBy="userRolle")
     * @ORM\JoinColumn(name="t_rolle_id", referencedColumnName="id")
     */
    private $rolle;


    public function getId()
    {
        return $this->id;
    }

    public function getTUserID(): ?int
    {
        return $this->t_user_ID;
    }

    public function setTUserID(int $t_user_ID): self
    {
        $this->t_user_ID = $t_user_ID;

        return $this;
    }

    public function getTRolleID(): ?int
    {
        return $this->t_rolle_ID;
    }

    public function setTRolleID(int $t_rolle_ID): self
    {
        $this->t_rolle_ID = $t_rolle_ID;

        return $this;
    }
}
