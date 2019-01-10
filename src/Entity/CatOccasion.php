<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * CatOccasion
 *
 * @ORM\Table(name="cat_occasion")
 * @ORM\Entity
 */
class CatOccasion
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_cat_occ", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="cat_occasion_id_cat_occ_seq", allocationSize=1, initialValue=1)
     */
    private $idCatOcc;

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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Attestation", inversedBy="idCatOcc")
     * @ORM\JoinTable(name="a_cat_occasion",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_cat_occ", referencedColumnName="id_cat_occ")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id", referencedColumnName="id")
     *   }
     * )
     */
    private $id;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->id = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getIdCatOcc(): ?int
    {
        return $this->idCatOcc;
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

    /**
     * @return Collection|Attestation[]
     */
    public function getId(): Collection
    {
        return $this->id;
    }

    public function addId(Attestation $id): self
    {
        if (!$this->id->contains($id)) {
            $this->id[] = $id;
        }

        return $this;
    }

    public function removeId(Attestation $id): self
    {
        if ($this->id->contains($id)) {
            $this->id->removeElement($id);
        }

        return $this;
    }

}
