<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ElementBiblio
 *
 * @ORM\Table(name="element_biblio")
 * @ORM\Entity
 */
class ElementBiblio extends AbstractEntity
{
    /**
     * @var \Element
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Element", inversedBy="elementBiblios", fetch="EXTRA_LAZY")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_element", referencedColumnName="id")
     * })
     */
    private $element;

    /**
     * @var \Biblio
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Biblio", inversedBy="elementBiblios", cascade={"persist"}, fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_biblio", referencedColumnName="id")
     * })
     */
    private $biblio;

    /**
     * @var string|null
     *
     * @ORM\Column(name="reference_element", type="string", length=255, nullable=true)
     */
    private $referenceElement;

    public function getReferenceElement(): ?string
    {
        return $this->referenceElement;
    }

    public function setReferenceElement(?string $referenceElement): self
    {
        $this->referenceElement = $referenceElement;
        return $this;
    }

    public function getElement(): ?Element
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

    public function __toString(): string
    {
        return "Elément #" . $this->getElement()->getId() . " / Biblio #" . $this->getBiblio()->getId();
    }

}
