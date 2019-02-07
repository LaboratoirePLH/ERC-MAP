<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Auteur
 *
 * @ORM\Table(name="auteur")
 * @ORM\Entity
 */
class Auteur
{
    use Traits\TranslatedName;

    /**
     * @var int
     *
     * @ORM\Column(name="id_auteur", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="auteur_id_auteur_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Titre", mappedBy="auteurs", fetch="EXTRA_LAZY")
     */
    private $titres;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Source", mappedBy="auteurs", fetch="EXTRA_LAZY")
     */
    private $sources;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->titres = new \Doctrine\Common\Collections\ArrayCollection();
        $this->sources = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return Collection|Titre[]
     */
    public function getTitres(): Collection
    {
        return $this->titres;
    }

    public function addTitre(Titre $titre): self
    {
        if (!$this->titres->contains($titre)) {
            $this->titres[] = $titre;
            $titre->addAuteur($this);
        }
        return $this;
    }

    public function removeTitre(Titre $titre): self
    {
        if ($this->titres->contains($titre)) {
            $this->titres->removeElement($titre);
            $titre->removeAuteur($this);
        }
        return $this;
    }

    /**
     * @return Collection|Source[]
     */
    public function getSources(): Collection
    {
        return $this->sources;
    }

    public function addSource(Source $source): self
    {
        if (!$this->sources->contains($source)) {
            $this->sources[] = $source;
            $source->addAuteur($this);
        }
        return $this;
    }

    public function removeSource(Source $source): self
    {
        if ($this->sources->contains($source)) {
            $this->sources->removeElement($source);
            $source->removeAuteur($this);
        }
        return $this;
    }

}
