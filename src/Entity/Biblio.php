<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Biblio
 *
 * @ORM\Table(name="biblio", indexes={@ORM\Index(name="IDX_D90CBB252B41ABF4", columns={"corpus_id"})})
 * @ORM\Entity
 */
class Biblio
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="biblio_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="titre_abr", type="string", length=255, nullable=true)
     */
    private $titreAbrege;

    /**
     * @var string|null
     *
     * @ORM\Column(name="titre_com", type="text", nullable=true)
     */
    private $titreComplet;

    /**
     * @var int|null
     *
     * @ORM\Column(name="annee", type="smallint", nullable=true)
     */
    private $annee;

    /**
     * @var string|null
     *
     * @ORM\Column(name="auteur_biblio", type="string", length=255, nullable=true)
     */
    private $auteurBiblio;

    /**
     * @var \Corpus
     *
     * @ORM\ManyToOne(targetEntity="Corpus")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="corpus_id", referencedColumnName="id")
     * })
     */
    private $corpus;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="SourceBiblio", mappedBy="biblio")
     */
    private $sourceBiblios;

    /**
     * @return Collection|SourceBiblio[]
     */
    public function getSourceBiblios(): ?Collection
    {
        return $this->sourceBiblios;
    }

    public function addSourceBiblio(SourceBiblio $sourceBiblio): self
    {
        if (!$this->sourceBiblios->contains($sourceBiblio)) {
            $this->sourceBiblios[] = $sourceBiblio;
        }
        return $this;
    }

    public function removeSourceBiblio(SourceBiblio $sourceBiblio): self
    {
        if ($this->sourceBiblios->contains($sourceBiblio)) {
            $this->sourceBiblios->removeElement($sourceBiblio);
        }
        return $this;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="ElementBiblio", mappedBy="biblio")
     */
    private $elementBiblios;

    /**
     * @return Collection|ElementBiblio[]
     */
    public function getElementBiblios(): ?Collection
    {
        return $this->elementBiblios;
    }

    public function addElementBiblio(ElementBiblio $elementBiblio): self
    {
        if (!$this->elementBiblios->contains($elementBiblio)) {
            $this->elementBiblios[] = $elementBiblio;
        }
        return $this;
    }

    public function removeElementBiblio(ElementBiblio $elementBiblio): self
    {
        if ($this->elementBiblios->contains($elementBiblio)) {
            $this->elementBiblios->removeElement($elementBiblio);
        }
        return $this;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->elementBiblios = new \Doctrine\Common\Collections\ArrayCollection();
        $this->sourceBiblios = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitreAbrege(): ?string
    {
        return $this->titreAbrege;
    }

    public function setTitreAbrege(?string $titreAbrege): self
    {
        $this->titreAbrege = $titreAbrege;

        return $this;
    }

    public function getTitreComplet(): ?string
    {
        return $this->titreComplet;
    }

    public function setTitreComplet(?string $titreComplet): self
    {
        $this->titreComplet = $titreComplet;

        return $this;
    }

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(?int $annee): self
    {
        $this->annee = $annee;

        return $this;
    }

    public function getAuteurBiblio(): ?string
    {
        return $this->auteurBiblio;
    }

    public function setAuteurBiblio(?string $auteurBiblio): self
    {
        $this->auteurBiblio = $auteurBiblio;

        return $this;
    }

    public function getCorpus(): ?Corpus
    {
        return $this->corpus;
    }

    public function setCorpus(?Corpus $corpus): self
    {
        $this->corpus = $corpus;

        return $this;
    }

    /**
     * @return Collection|Elements[]
     */
    public function getIdElt(): Collection
    {
        return $this->idElt;
    }

    public function addIdElt(Elements $idElt): self
    {
        if (!$this->idElt->contains($idElt)) {
            $this->idElt[] = $idElt;
        }

        return $this;
    }

    public function removeIdElt(Elements $idElt): self
    {
        if ($this->idElt->contains($idElt)) {
            $this->idElt->removeElement($idElt);
        }

        return $this;
    }

}
