<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SeoRepository")
 * @ORM\Table(name="t_seo")
 */
class Seo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $optimierung_aufgeschaltet;

    /**
     * @ORM\Column(type="integer")
     */
    private $t_url_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $t_seo_art_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */


    private $t_user_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Url", inversedBy="seo")
     * @ORM\JoinColumn(name="t_url_id", referencedColumnName="id")
     */
    private $url;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SeoTyp", inversedBy="seo")
     * @ORM\JoinColumn(name="t_seo_art_id", referencedColumnName="id")
     */
    private $seoTyp;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Mitarbeiter", inversedBy="seo")
     * @ORM\JoinColumn(name="t_user_id", referencedColumnName="id")
     */
    private $mitarbeiter;

    public function getId()
    {
        return $this->id;
    }

    public function getOptimierungAufgeschaltet(): ?\DateTimeInterface
    {
        return $this->optimierung_aufgeschaltet;
    }

    public function setOptimierungAufgeschaltet(?\DateTimeInterface $optimierung_aufgeschaltet): self
    {
        $this->optimierung_aufgeschaltet = $optimierung_aufgeschaltet;

        return $this;
    }

    public function getTUrlId(): ?int
    {
        return $this->t_url_id;
    }

    public function setTUrlId(int $t_url_id): self
    {
        $this->t_url_id = $t_url_id;

        return $this;
    }

    public function getTSeoArtId(): ?int
    {
        return $this->t_seo_art_id;
    }

    public function setTSeoArtId(?int $t_seo_art_id): self
    {
        $this->t_seo_art_id = $t_seo_art_id;

        return $this;
    }

    public function getTUserId(): ?int
    {
        return $this->t_user_id;
    }

    public function setTUserId(?int $t_user_id): self
    {
        $this->t_user_id = $t_user_id;

        return $this;
    }
}
