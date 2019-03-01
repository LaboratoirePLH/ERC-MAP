<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Projet
 *
 * @ORM\Table(name="projet")
 * @ORM\Entity
 */
class Projet
{
    use Traits\TranslatedName;
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Chercheur", inversedBy="projets")
     * @ORM\JoinTable(name="projet_chercheur",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_projet", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_chercheur", referencedColumnName="id")
     *   }
     * )
     */
    private $chercheurs;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->chercheurs = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return Collection|Chercheur[]
     */
    public function getChercheurs(): Collection
    {
        return $this->chercheurs;
    }

    public function addChercheur(Chercheur $chercheur): self
    {
        if (!$this->chercheurs->contains($chercheur)) {
            $this->chercheurs[] = $chercheur;
        }
        return $this;
    }

    public function removeChercheur(Chercheur $chercheur): self
    {
        if ($this->chercheurs->contains($chercheur)) {
            $this->chercheurs->removeElement($chercheur);
        }
        return $this;
    }
}
