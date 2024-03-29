<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Biblio
 *
 * @ORM\Table(name="biblio")
 * @ORM\Entity(repositoryClass="App\Repository\BibliographyRepository")
 */
class Biblio extends AbstractEntity
{
    use Traits\EntityId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="titre_abrege", type="text", nullable=true)
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

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="SourceBiblio", mappedBy="biblio", orphanRemoval=true)
     */
    private $sourceBiblios;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="ElementBiblio", mappedBy="biblio", orphanRemoval=true)
     */
    private $elementBiblios;

    /**
     * @ORM\ManyToOne(targetEntity="VerrouEntite", inversedBy="biblios", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="verrou_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     * })
     */
    private $verrou;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->sourceBiblios = new ArrayCollection();
        $this->elementBiblios = new ArrayCollection();
    }

    /**
     * Clone magic method
     */
    public function __clone()
    {
        if ($this->id !== null) {
            $this->id             = null;
            $this->verrou         = null;
            $this->sourceBiblios  = new ArrayCollection();
            $this->elementBiblios = new ArrayCollection();
        }
    }

    public function getTitreAbrege(): ?string
    {
        return $this->sanitizeHtml($this->titreAbrege);
    }

    public function setTitreAbrege(?string $titreAbrege): self
    {
        $this->titreAbrege = $this->sanitizeHtml($titreAbrege);

        return $this;
    }

    public function getTitreComplet(): ?string
    {
        return $this->sanitizeHtml($this->titreComplet);
    }

    public function setTitreComplet(?string $titreComplet): self
    {
        $this->titreComplet = $this->sanitizeHtml($titreComplet);

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
        if ($this->getEstCorpus()) {
            return sprintf("Corpus : %s", $this->getTitreAbrege());
        } else {
            return sprintf("%s, %s (%d)", $this->auteurBiblio, $this->titreAbrege, $this->annee);
        }
    }

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

    public function __toString(): string
    {
        return $this->getAffichage();
    }

    public function getVerrou(): ?VerrouEntite
    {
        return $this->verrou;
    }

    public function setVerrou(?VerrouEntite $verrou): self
    {
        $this->verrou = $verrou;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id'           => $this->id,
            'titreAbrege'  => $this->titreAbrege,
            'titreComplet' => $this->titreComplet,
            'annee'        => $this->annee,
            'auteur'       => $this->auteurBiblio
        ];
    }
}
