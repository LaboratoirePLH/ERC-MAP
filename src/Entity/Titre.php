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
    use Traits\EntityId;
    use Traits\TranslatedName;

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
     * Constructor
     */
    public function __construct()
    {
        $this->auteurs = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
