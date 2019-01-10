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
    private $titreAbr;

    /**
     * @var string|null
     *
     * @ORM\Column(name="titre_com", type="text", nullable=true)
     */
    private $titreCom;

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
     * @ORM\ManyToMany(targetEntity="Elements", inversedBy="idBiblio")
     * @ORM\JoinTable(name="trouve_elt",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_biblio", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_elt", referencedColumnName="id")
     *   }
     * )
     */
    private $idElt;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idElt = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitreAbr(): ?string
    {
        return $this->titreAbr;
    }

    public function setTitreAbr(?string $titreAbr): self
    {
        $this->titreAbr = $titreAbr;

        return $this;
    }

    public function getTitreCom(): ?string
    {
        return $this->titreCom;
    }

    public function setTitreCom(?string $titreCom): self
    {
        $this->titreCom = $titreCom;

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
