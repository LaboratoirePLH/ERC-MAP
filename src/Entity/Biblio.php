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
     * @var bool|null
     *
     * @ORM\Column(name="est_corpus", type="boolean", nullable=true)
     */
    private $estCorpus;

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

    public function getEstCorpus(): ?bool
    {
        return $this->estCorpus;
    }

    public function setEstCorpus(?bool $estCorpus): self
    {
        $this->estCorpus = $estCorpus;
        return $this;
    }

    public function getAffichage(): string
    {
        if($this->getEstCorpus()){
            return sprintf("Corpus : %s", $this->getTitreAbrege());
        } else {
            return sprintf("Biblio : %s, %s (%d)", $this->auteurBiblio, $this->titreAbrege, $this->annee);
        }
    }

}
