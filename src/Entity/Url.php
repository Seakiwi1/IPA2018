<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UrlRepository")
 * @ORM\Table(name="t_url")
 */
class Url
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
    private $txt_www;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Seo",  mappedBy="url")
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

    public function getTxtWww(): ?string
    {
        return $this->txt_www;
    }

    public function setTxtWww(?string $txt_www): self
    {
        $this->txt_www = $txt_www;

        return $this;
    }
}
