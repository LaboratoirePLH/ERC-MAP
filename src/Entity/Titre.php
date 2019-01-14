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
     * @ORM\Column(name="id_titre", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="titre_id_titre_seq", allocationSize=1, initialValue=1)
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
     * @ORM\JoinTable(name="a_ecrit",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_titre", referencedColumnName="id_titre")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_auteur", referencedColumnName="id_auteur")
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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Source", mappedBy="titresCites")
     */
    private $sourcesCitees;

    /**
     * @return Collection|Source[]
     */
    public function getSourcesCitees(): Collection
    {
        return $this->sourceCitees;
    }

    public function addSourcesCitee(Source $sourcesCitee): self
    {
        if (!$this->sourcesCitees->contains($sourcesCitee)) {
            $this->sourcesCitees[] = $sourcesCitee;
            $sourcesCitee->addTitresCite($this);
        }
        return $this;
    }

    public function removeSourcesCitee(Source $sourcesCitee): self
    {
        if ($this->sourcesCitees->contains($sourcesCitee)) {
            $this->sourcesCitees->removeElement($sourcesCitee);
            $sourcesCitee->removeTitresCite($this);
        }
        return $this;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->auteurs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->sourcesCitees = new \Doctrine\Common\Collections\ArrayCollection();
    }
}
