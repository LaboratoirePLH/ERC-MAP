<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ContientElement
 *
 * @ORM\Table(name="contient_element")
 * @ORM\Entity
 */
class ContientElement extends AbstractEntity
{
    /**
     * @var \Attestation
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Attestation", inversedBy="contientElements", fetch="EXTRA_LAZY")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_attestation", referencedColumnName="id")
     * })
     */
    private $attestation;

    /**
     * @var \Element
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Element", inversedBy="contientElements", fetch="EXTRA_LAZY")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_element", referencedColumnName="id")
     * })
     */
    private $element;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="position_element", type="smallint", nullable=false)
     * @Assert\NotBlank
     * @Assert\GreaterThan(0)
     */
    private $positionElement;

    /**
     * @var string|null
     *
     * @ORM\Column(name="etat_morphologique", type="string", length=255, nullable=true)
     */
    private $etatMorphologique;


    /**
     * @var bool|null
     *
     * @ORM\Column(name="suffixe", type="boolean", nullable=true)
     */
    private $suffixe;

    /**
     * @var string|null
     *
     * @ORM\Column(name="en_contexte", type="text", nullable=true)
     */
    private $enContexte;

    /**
     * @var \CategorieElement
     *
     * @ORM\ManyToOne(targetEntity="CategorieElement")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_categorie_element", referencedColumnName="id")
     * })
     */
    private $categorieElement;

    /**
     * @var \GenreElement
     *
     * @ORM\ManyToOne(targetEntity="GenreElement")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_genre_element", referencedColumnName="id")
     * })
     */
    private $genreElement;

    /**
     * @var \NombreElement
     *
     * @ORM\ManyToOne(targetEntity="NombreElement")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_nombre_element", referencedColumnName="id")
     * })
     */
    private $nombreElement;

    public function getEtatMorphologique(): ?string
    {
        return $this->etatMorphologique;
    }

    public function setEtatMorphologique(?string $etatMorphologique): self
    {
        $this->etatMorphologique = $etatMorphologique;
        return $this;
    }

    public function getPositionElement(): ?int
    {
        return $this->positionElement;
    }

    public function setPositionElement(int $positionElement): self
    {
        $this->positionElement = $positionElement;
        return $this;
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

    public function getAttestation(): ?Attestation
    {
        return $this->attestation;
    }

    public function setAttestation(?Attestation $attestation): self
    {
        $this->attestation = $attestation;
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

    public function getCategorieElement(): ?CategorieElement
    {
        return $this->categorieElement;
    }

    public function setCategorieElement(?CategorieElement $categorieElement): self
    {
        $this->categorieElement = $categorieElement;
        return $this;
    }

    public function getGenreElement(): ?GenreElement
    {
        return $this->genreElement;
    }

    public function setGenreElement(?GenreElement $genreElement): self
    {
        $this->genreElement = $genreElement;
        return $this;
    }

    public function getNombreElement(): ?NombreElement
    {
        return $this->nombreElement;
    }

    public function setNombreElement(?NombreElement $nombreElement): self
    {
        $this->nombreElement = $nombreElement;
        return $this;
    }

    public function __toString(): string
    {
        return "Attestation #" . $this->getAttestation()->getId() . " / Elément #" . $this->getElement()->getId();
    }

    public function getEnContexte(): ?string
    {
        return $this->enContexte;
    }

    public function setEnContexte(?string $enContexte): self
    {
        $this->enContexte = $enContexte;

        return $this;
    }
}
