<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ContientElement
 *
 * @ORM\Table(name="contient_elt", indexes={@ORM\Index(name="fki_contient_elt_id_categorie_fkey", columns={"id_categorie_elt"}), @ORM\Index(name="IDX_EE80CB35A1342FAE", columns={"id_attest"}), @ORM\Index(name="IDX_EE80CB35B1D9A90D", columns={"id_elt"}), @ORM\Index(name="IDX_EE80CB356DD572C8", columns={"id_genre"}), @ORM\Index(name="IDX_EE80CB351CA724CB", columns={"id_nombre"})})
 * @ORM\Entity
 */
class ContientElement
{
    /**
     * @var int
     *
     * @ORM\Column(name="position_elt", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $positionElt;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="suffixe", type="boolean", nullable=true)
     */
    private $suffixe;

    /**
     * @var string|null
     *
     * @ORM\Column(name="restit_ss", type="string", length=255, nullable=true)
     */
    private $restitSs;

    /**
     * @var string|null
     *
     * @ORM\Column(name="restit_avec", type="string", length=255, nullable=true)
     */
    private $restitAvec;

    /**
     * @var \Attestation
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Attestation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_attest", referencedColumnName="id")
     * })
     */
    private $idAttest;

    /**
     * @var \CategorieElement
     *
     * @ORM\ManyToOne(targetEntity="CategorieElement")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_categorie_elt", referencedColumnName="id_cat_elt")
     * })
     */
    private $idCategorieElement;

    /**
     * @var \Element
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Element")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_elt", referencedColumnName="id")
     * })
     */
    private $idElt;

    /**
     * @var \GenreElement
     *
     * @ORM\ManyToOne(targetEntity="GenreElement")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_genre", referencedColumnName="id_genre")
     * })
     */
    private $idGenre;

    /**
     * @var \NombreElement
     *
     * @ORM\ManyToOne(targetEntity="NombreElement")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_nombre", referencedColumnName="id_nombre")
     * })
     */
    private $idNombre;

    public function getPositionElt(): ?int
    {
        return $this->positionElt;
    }

    public function getSuffixe(): ?bool
    {
        return $this->suffixe;
    }

    public function setSuffixe(?bool $suffixe): self
    {
        $this->suffixe = $suffixe;

        return $this;
    }

    public function getRestitSs(): ?string
    {
        return $this->restitSs;
    }

    public function setRestitSs(?string $restitSs): self
    {
        $this->restitSs = $restitSs;

        return $this;
    }

    public function getRestitAvec(): ?string
    {
        return $this->restitAvec;
    }

    public function setRestitAvec(?string $restitAvec): self
    {
        $this->restitAvec = $restitAvec;

        return $this;
    }

    public function getIdAttest(): ?Attestation
    {
        return $this->idAttest;
    }

    public function setIdAttest(?Attestation $idAttest): self
    {
        $this->idAttest = $idAttest;

        return $this;
    }

    public function getIdCategorieElt(): ?CategorieElement
    {
        return $this->idCategorieElt;
    }

    public function setIdCategorieElt(?CategorieElement $idCategorieElt): self
    {
        $this->idCategorieElt = $idCategorieElt;

        return $this;
    }

    public function getIdElt(): ?Element
    {
        return $this->idElt;
    }

    public function setIdElt(?Element $idElt): self
    {
        $this->idElt = $idElt;

        return $this;
    }

    public function getIdGenre(): ?GenreElement
    {
        return $this->idGenre;
    }

    public function setIdGenre(?GenreElement $idGenre): self
    {
        $this->idGenre = $idGenre;

        return $this;
    }

    public function getIdNombre(): ?NombreElement
    {
        return $this->idNombre;
    }

    public function setIdNombre(?NombreElement $idNombre): self
    {
        $this->idNombre = $idNombre;

        return $this;
    }


}
