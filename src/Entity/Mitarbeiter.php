<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MitarbeiterRepository")
 * @ORM\Table(name="t_user")
 */
class Mitarbeiter
{

    const ACTIVE_USER = 361;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $vorname;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $jahresziel_seo;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $jahresziel_lp;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserRolle",  mappedBy="mitarbeiter")
     */
    private $userRolle;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Seo",  mappedBy="mitarbeiter")
     */
    private $seo;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Jahresziele",  mappedBy="mitarbeiter")
     */
    private $jahreszieleSeo;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserRechte",  mappedBy="mitarbeiter")
     */

    private $userRechte;


    public function __construct()
    {
        $this->Mitarbeiter = new ArrayCollection();
        $this->userRolle = new ArrayCollection();
        $this->seo = new ArrayCollection();
        $this->jahreszieleSeo = new ArrayCollection();
    }

    /**
     * @return Collection|UserRolle[]
     */
    public function getUserRolle()
    {
        return $this->userRolle;
    }

    /**
     * @return Collection|Jahresziele[]
     */
    public function getJahreszieleSeo()
    {
        return $this->jahreszieleSeo;
    }

    /**
     * @return Collection|JahreszieleZs[]
     */
    public function getJahreszieleZs()
    {
        return $this->jahreszieleZs;
    }

    /**
     * @return Collection|UserRolle[]
     */
    public function getSeo()
    {
        return $this->seo;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getVorname(): ?string
    {
        return $this->vorname;
    }

    public function setVorname(?string $vorname): self
    {
        $this->vorname = $vorname;

        return $this;
    }

    public function getJahreszielSeo(): ?int
    {
        return $this->jahresziel_seo;
    }

    public function setJahreszielSeo(?int $jahresziel_seo): self
    {
        $this->jahresziel_seo = $jahresziel_seo;

        return $this;
    }

    public function getJahreszielLp(): ?int
    {
        return $this->jahresziel_lp;
    }

    public function setJahreszielLp(?int $jahresziel_lp): self
    {
        $this->jahresziel_lp = $jahresziel_lp;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }
}
