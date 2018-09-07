<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RechteGamsRepository")
 * @ORM\Table(name="t_rechte_gams")
 */
class RechteGams
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $titel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserRechte",  mappedBy="rechteGams")
     */

    private $userRechte;

    public function getId()
    {
        return $this->id;
    }

    public function getTitel(): ?string
    {
        return $this->titel;
    }

    public function setTitel(?string $titel): self
    {
        $this->titel = $titel;

        return $this;
    }
}
