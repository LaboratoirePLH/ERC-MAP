<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TrouveSource
 *
 * @ORM\Table(name="trouve_source", indexes={@ORM\Index(name="IDX_4E6330D7FF3B0EC8", columns={"id_biblio"})})
 * @ORM\Entity
 */
class TrouveSource
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_source", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idSource;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="ed_ppale", type="boolean", nullable=true)
     */
    private $edPpale;

    /**
     * @var string|null
     *
     * @ORM\Column(name="com_bib_fr", type="text", nullable=true)
     */
    private $comBibFr;

    /**
     * @var string|null
     *
     * @ORM\Column(name="com_bib_en", type="text", nullable=true)
     */
    private $comBibEn;

    /**
     * @var string|null
     *
     * @ORM\Column(name="bib_ref_source", type="string", length=255, nullable=true)
     */
    private $bibRefSource;

    /**
     * @var \Biblio
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Biblio")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_biblio", referencedColumnName="id")
     * })
     */
    private $idBiblio;

    public function getIdSource(): ?int
    {
        return $this->idSource;
    }

    public function getEdPpale(): ?bool
    {
        return $this->edPpale;
    }

    public function setEdPpale(?bool $edPpale): self
    {
        $this->edPpale = $edPpale;

        return $this;
    }

    public function getComBibFr(): ?string
    {
        return $this->comBibFr;
    }

    public function setComBibFr(?string $comBibFr): self
    {
        $this->comBibFr = $comBibFr;

        return $this;
    }

    public function getComBibEn(): ?string
    {
        return $this->comBibEn;
    }

    public function setComBibEn(?string $comBibEn): self
    {
        $this->comBibEn = $comBibEn;

        return $this;
    }

    public function getBibRefSource(): ?string
    {
        return $this->bibRefSource;
    }

    public function setBibRefSource(?string $bibRefSource): self
    {
        $this->bibRefSource = $bibRefSource;

        return $this;
    }

    public function getIdBiblio(): ?Biblio
    {
        return $this->idBiblio;
    }

    public function setIdBiblio(?Biblio $idBiblio): self
    {
        $this->idBiblio = $idBiblio;

        return $this;
    }


}
