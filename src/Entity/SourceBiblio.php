<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SourceBiblio
 *
 * @ORM\Table(name="source_biblio")
 * @ORM\Entity
 */
class SourceBiblio
{
    use Traits\TranslatedComment;

    /**
     * @var \Source
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Source", inversedBy="sourceBiblios", fetch="EXTRA_LAZY")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_source", referencedColumnName="id")
     * })
     */
    private $source;

    /**
     * @var \Biblio
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Biblio", cascade={"persist"}, fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_biblio", referencedColumnName="id")
     * })
     */
    private $biblio;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="edition_principale", type="boolean", nullable=true)
     */
    private $editionPrincipale;

    /**
     * @var string|null
     *
     * @ORM\Column(name="reference_source", type="string", length=255, nullable=true)
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
