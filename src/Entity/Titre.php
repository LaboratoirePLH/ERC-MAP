<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Titre
 *
 * @ORM\Table(name="titre")
 * @ORM\Entity
 */
class Titre
{
    use Traits\TranslatedName;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
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
     * @ORM\ManyToMany(targetEntity="Auteur", inversedBy="titres")
     * @ORM\JoinTable(name="titre_auteur",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_titre", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_auteur", referencedColumnName="id")
     *   }
     * )
     */
    private $auteurs;

    /**
     * @return Collection|Auteur[]
     */
    public function getAuteurs(): Collection
    {
        return $this->auteurs;
    }

    public function addAuteur(Auteur $auteur): self
    {
        if (!$this->auteurs->contains($auteur)) {
            $this->auteurs[] = $auteur;
        }
        return $this;
    }

    public function removeAuteur(Auteur $auteur): self
    {
        if ($this->auteurs->contains($auteur)) {
            $this->auteurs->removeElement($auteur);
        }
        return $this;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->auteurs = new \Doctrine\Common\Collections\ArrayCollection();
    }

    private function _affichage($lang){
        $auteurs = [];
        foreach($this->getAuteurs() as $a){
            $auteurs[] = $a->getNom($lang);
        }
        return \sprintf("%s (%s)", $this->getNom($lang), implode(', ', $auteurs));
    }
    public function getAffichageFr(): string
    {
        return $this->_affichage('fr');
    }

    public function getAffichageEn(): string
    {
        return $this->_affichage('en');
    }
}
