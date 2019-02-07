<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SourceBiblio
 *
 * @ORM\Table(name="trouve_source")
 * @ORM\Entity
 */
class SourceBiblio
{
    /**
     * @var \Source
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Source", inversedBy="sourceBiblios")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_source", referencedColumnName="id")
     * })
     */
    private $source;

    /**
     * @var \Biblio
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Biblio", inversedBy="sourceBiblios", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_biblio", referencedColumnName="id")
     * })
     */
    private $biblio;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="ed_ppale", type="boolean", nullable=true)
     */
    private $editionPrincipale;

    /**
     * @var string|null
     *
     * @ORM\Column(name="com_bib_fr", type="text", nullable=true)
     */
    private $commentaireBiblioFr;

    /**
     * @var string|null
     *
     * @ORM\Column(name="com_bib_en", type="text", nullable=true)
     */
    private $commentaireBiblioEn;

    /**
     * @var string|null
     *
     * @ORM\Column(name="bib_ref_source", type="string", length=255, nullable=true)
     */
    private $referenceSource;


    public function getSource(): ?Source
    {
        return $this->source;
    }

    public function setSource(?Source $source): self
    {
        $this->source = $source;
        return $this;
    }

    public function getBiblio(): ?Biblio
    {
        return $this->biblio;
    }

    public function setBiblio(?Biblio $biblio): self
    {
        $this->biblio = $biblio;
        return $this;
    }

    public function getEditionPrincipale(): ?bool
    {
        return $this->editionPrincipale;
    }

    public function setEditionPrincipale(?bool $editionPrincipale): self
    {
        $this->editionPrincipale = $editionPrincipale;
        return $this;
    }

    public function getCommentaireBiblioFr(): ?string
    {
        return $this->commentaireBiblioFr;
    }

    public function setCommentaireBiblioFr(?string $commentaireBiblioFr): self
    {
        $this->commentaireBiblioFr = $commentaireBiblioFr;
        return $this;
    }

    public function getCommentaireBiblioEn(): ?string
    {
        return $this->commentaireBiblioEn;
    }

    public function setCommentaireBiblioEn(?string $commentaireBiblioEn): self
    {
        $this->commentaireBiblioEn = $commentaireBiblioEn;
        return $this;
    }

    public function getCommentaireBiblio(?string $lang): ?string
    {
        if($lang == 'fr'){
            return $this->commentaireBiblioFr;
        } else {
            return $this->commentaireBiblioEn;
        }
    }

    public function getReferenceSource(): ?string
    {
        return $this->referenceSource;
    }

    public function setReferenceSource(?string $referenceSource): self
    {
        $this->referenceSource = $referenceSource;
        return $this;
    }




}
