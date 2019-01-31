<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ElementBiblio
 *
 * @ORM\Table(name="trouve_elt")
 * @ORM\Entity
 */
class ElementBiblio
{
    /**
     * @var \Element
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Element", inversedBy="elementBiblios")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_elt", referencedColumnName="id")
     * })
     */
    private $element;

    /**
     * @var \Biblio
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Biblio", inversedBy="elementBiblios")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_biblio", referencedColumnName="id")
     * })
     */
    private $biblio;

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
     * @ORM\Column(name="bib_ref_elt", type="string", length=255, nullable=true)
     */
    private $referenceElement;


    public function getElement(): ?Elemen
    {
        return $this->element;
    }

    public function setElement(?Element $element): self
    {
        $this->element = $element;
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

    public function getReferenceElement(): ?string
    {
        return $this->referenceElement;
    }

    public function setReferenceElement(?string $referenceElement): self
    {
        $this->referenceElement = $referenceElement;
        return $this;
    }




}
