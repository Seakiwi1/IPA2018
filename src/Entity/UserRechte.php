<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRechteRepository")
 * @ORM\Table(name="t_user_rechte")
 */
class UserRechte
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
     * @ORM\Column(type="integer", nullable=true)
     */
    private $t_rechte_gams_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Mitarbeiter", inversedBy="userRechte")
     * @ORM\JoinColumn(name="t_user_id", referencedColumnName="id")
     */
    private $mitarbeiter;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RechteGams", inversedBy="userRechte")
     * @ORM\JoinColumn(name="t_rechte_gams_id", referencedColumnName="id")
     */
    private $rechteGams;

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

    public function getTRechteGamsId(): ?int
    {
        return $this->t_rechte_gams_id;
    }

    public function setTRechteGamsId(?int $t_rechte_gams_id): self
    {
        $this->t_rechte_gams_id = $t_rechte_gams_id;

        return $this;
    }
}
