<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\JahreszieleRepository")
 * @ORM\Table(name="t_user_jahresziele")
 */
class Jahresziele
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $t_user_ID;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $art;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $jahr;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */

    private $ziel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Mitarbeiter", inversedBy="jahreszieleSeo")
     * @ORM\JoinColumn(name="t_user_id", referencedColumnName="id")
     */

    private $mitarbeiter;

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

    public function getArt(): ?string
    {
        return $this->art;
    }

    public function setArt(string $art): self
    {
        $this->art = $art;

        return $this;
    }

    public function getJahr(): ?int
    {
        return $this->jahr;
    }

    public function setJahr(int $jahr): self
    {
        $this->jahr = $jahr;

        return $this;
    }

    public function getZiel(): ?int
    {
        return $this->ziel;
    }

    public function setZiel(int $ziel): self
    {
        $this->ziel = $ziel;

        return $this;
    }
}
