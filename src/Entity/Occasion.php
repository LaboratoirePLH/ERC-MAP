<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Occasion
 *
 * @ORM\Table(name="occasion", indexes={@ORM\Index(name="IDX_8A66DCE57CDB21E9", columns={"id_cat_occ"})})
 * @ORM\Entity
 */
class Occasion
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_occ", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="occasion_id_occ_seq", allocationSize=1, initialValue=1)
     */
    private $idOcc;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nom", type="string", length=100, nullable=true)
     */
    private $nom;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * @var \CatOccasion
     *
     * @ORM\ManyToOne(targetEntity="CatOccasion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_cat_occ", referencedColumnName="id_cat_occ")
     * })
     */
    private $idCatOcc;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Attestation", inversedBy="idOcc")
     * @ORM\JoinTable(name="a_occasion",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_occ", referencedColumnName="id_occ")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_attest", referencedColumnName="id")
     *   }
     * )
     */
    private $idAttest;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idAttest = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getIdOcc(): ?int
    {
        return $this->idOcc;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getIdCatOcc(): ?CatOccasion
    {
        return $this->idCatOcc;
    }

    public function setIdCatOcc(?CatOccasion $idCatOcc): self
    {
        $this->idCatOcc = $idCatOcc;

        return $this;
    }

    /**
     * @return Collection|Attestation[]
     */
    public function getIdAttest(): Collection
    {
        return $this->idAttest;
    }

    public function addIdAttest(Attestation $idAttest): self
    {
        if (!$this->idAttest->contains($idAttest)) {
            $this->idAttest[] = $idAttest;
        }

        return $this;
    }

    public function removeIdAttest(Attestation $idAttest): self
    {
        if ($this->idAttest->contains($idAttest)) {
            $this->idAttest->removeElement($idAttest);
        }

        return $this;
    }

}
