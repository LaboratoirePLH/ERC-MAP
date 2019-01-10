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
    /**
     * @var int
     *
     * @ORM\Column(name="id_auteur", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="auteur_id_auteur_seq", allocationSize=1, initialValue=1)
     */
    private $idAuteur;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Titre", mappedBy="idAuteur")
     */
    private $idTitre;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Source", mappedBy="idAuteur")
     */
    private $idSource;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idTitre = new \Doctrine\Common\Collections\ArrayCollection();
        $this->idSource = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getIdAuteur(): ?int
    {
        return $this->idAuteur;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
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

    /**
     * @return Collection|Titre[]
     */
    public function getIdTitre(): Collection
    {
        return $this->idTitre;
    }

    public function addIdTitre(Titre $idTitre): self
    {
        if (!$this->idTitre->contains($idTitre)) {
            $this->idTitre[] = $idTitre;
            $idTitre->addIdAuteur($this);
        }

        return $this;
    }

    public function removeIdTitre(Titre $idTitre): self
    {
        if ($this->idTitre->contains($idTitre)) {
            $this->idTitre->removeElement($idTitre);
            $idTitre->removeIdAuteur($this);
        }

        return $this;
    }

    /**
     * @return Collection|Source[]
     */
    public function getIdSource(): Collection
    {
        return $this->idSource;
    }

    public function addIdSource(Source $idSource): self
    {
        if (!$this->idSource->contains($idSource)) {
            $this->idSource[] = $idSource;
            $idSource->addIdAuteur($this);
        }

        return $this;
    }

    public function removeIdSource(Source $idSource): self
    {
        if ($this->idSource->contains($idSource)) {
            $this->idSource->removeElement($idSource);
            $idSource->removeIdAuteur($this);
        }

        return $this;
    }

}
