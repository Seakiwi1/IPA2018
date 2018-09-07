<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SeoTypRepository")
 * @ORM\Table(name="t_seo_art")
 */
class SeoTyp
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
    private $art;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Seo",  mappedBy="seoTyp")
     */
    private $seo;

    public function __construct()
    {
        $this->seo = new ArrayCollection();
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

    public function getArt(): ?string
    {
        return $this->art;
    }

    public function setArt(?string $art): self
    {
        $this->art = $art;

        return $this;
    }
}
