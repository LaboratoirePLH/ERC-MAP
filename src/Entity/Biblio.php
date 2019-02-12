<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Biblio
 *
 * @ORM\Table(name="biblio")
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
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="titre_abrege", type="string", length=255, nullable=true)
     */
    private $titreAbrege;

    /**
     * @var string|null
     *
     * @ORM\Column(name="titre_complet", type="text", nullable=true)
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
     * @ORM\ManyToOne(targetEntity="Corpus", cascade={"persist"}, fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="corpus_id", referencedColumnName="id")
     * })
     */
    private $corpus;

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

    public function getAffichage(): string
    {
        return \sprintf("%s, %s (%i)", $this->auteurBiblio, $this->titreAbrege, $this->annee);
    }

}
