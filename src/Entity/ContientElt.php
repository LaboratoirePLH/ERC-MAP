<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ContientElt
 *
 * @ORM\Table(name="contient_elt", indexes={@ORM\Index(name="fki_contient_elt_id_categorie_fkey", columns={"id_categorie_elt"}), @ORM\Index(name="IDX_EE80CB35A1342FAE", columns={"id_attest"}), @ORM\Index(name="IDX_EE80CB35B1D9A90D", columns={"id_elt"}), @ORM\Index(name="IDX_EE80CB356DD572C8", columns={"id_genre"}), @ORM\Index(name="IDX_EE80CB351CA724CB", columns={"id_nombre"})})
 * @ORM\Entity
 */
class ContientElt
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
     * @var \CategorieElt
     *
     * @ORM\ManyToOne(targetEntity="CategorieElt")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_categorie_elt", referencedColumnName="id_cat_elt")
     * })
     */
    private $idCategorieElt;

    /**
     * @var \Elements
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Elements")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_elt", referencedColumnName="id")
     * })
     */
    private $idElt;

    /**
     * @var \GenreElt
     *
     * @ORM\ManyToOne(targetEntity="GenreElt")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_genre", referencedColumnName="id_genre")
     * })
     */
    private $idGenre;

    /**
     * @var \NombreElt
     *
     * @ORM\ManyToOne(targetEntity="NombreElt")
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

    public function getIdCategorieElt(): ?CategorieElt
    {
        return $this->idCategorieElt;
    }

    public function setIdCategorieElt(?CategorieElt $idCategorieElt): self
    {
        $this->idCategorieElt = $idCategorieElt;

        return $this;
    }

    public function getIdElt(): ?Elements
    {
        return $this->idElt;
    }

    public function setIdElt(?Elements $idElt): self
    {
        $this->idElt = $idElt;

        return $this;
    }

    public function getIdGenre(): ?GenreElt
    {
        return $this->idGenre;
    }

    public function setIdGenre(?GenreElt $idGenre): self
    {
        $this->idGenre = $idGenre;

        return $this;
    }

    public function getIdNombre(): ?NombreElt
    {
        return $this->idNombre;
    }

    public function setIdNombre(?NombreElt $idNombre): self
    {
        $this->idNombre = $idNombre;

        return $this;
    }


}
