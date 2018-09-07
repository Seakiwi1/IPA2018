<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RolleRepository")
 * @ORM\Table(name="t_rolle")
 */
class Rolle
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $txt_rolle_de;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserRolle",  mappedBy="rolle")
     */
    private $userRolle;

    public function __construct()
    {
        $this->userRolle = new ArrayCollection();
    }

    /**
     * @return Collection|UserRolle[]
     */
    public function getUserRolle()
    {
        return $this->userRolle;
    }


    public function getId()
    {
        return $this->id;
    }

    public function getTxtRolleDe(): ?string
    {
        return $this->txt_rolle_de;
    }

    public function setTxtRolleDe(string $txt_rolle_de): self
    {
        $this->txt_rolle_de = $txt_rolle_de;

        return $this;
    }
}
