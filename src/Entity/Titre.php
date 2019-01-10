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
    /**
     * @var int
     *
     * @ORM\Column(name="id_titre", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="titre_id_titre_seq", allocationSize=1, initialValue=1)
     */
    private $idTitre;

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
     * @ORM\ManyToMany(targetEntity="Auteur", inversedBy="idTitre")
     * @ORM\JoinTable(name="a_ecrit",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_titre", referencedColumnName="id_titre")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_auteur", referencedColumnName="id_auteur")
     *   }
     * )
     */
    private $idAuteur;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Source", mappedBy="idTitre")
     */
    private $idSource;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idAuteur = new \Doctrine\Common\Collections\ArrayCollection();
        $this->idSource = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getIdTitre(): ?int
    {
        return $this->idTitre;
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
     * @return Collection|Auteur[]
     */
    public function getIdAuteur(): Collection
    {
        return $this->idAuteur;
    }

    public function addIdAuteur(Auteur $idAuteur): self
    {
        if (!$this->idAuteur->contains($idAuteur)) {
            $this->idAuteur[] = $idAuteur;
        }

        return $this;
    }

    public function removeIdAuteur(Auteur $idAuteur): self
    {
        if ($this->idAuteur->contains($idAuteur)) {
            $this->idAuteur->removeElement($idAuteur);
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
            $idSource->addIdTitre($this);
        }

        return $this;
    }

    public function removeIdSource(Source $idSource): self
    {
        if ($this->idSource->contains($idSource)) {
            $this->idSource->removeElement($idSource);
            $idSource->removeIdTitre($this);
        }

        return $this;
    }

}
